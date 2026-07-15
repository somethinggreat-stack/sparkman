<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\WebhookEvent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Overview / KPIs. */
    public function index()
    {
        $paidBase = fn () => Payment::where('status', 'paid');

        $stats = [
            'total_submissions' => Lead::count(),
            'today_submissions' => Lead::whereDate('created_at', today())->count(),
            'gross'             => (clone $paidBase())->sum('amount'),
            'refunded'          => Payment::where('status', 'refunded')->sum('amount'),
            'today_revenue'     => (clone $paidBase())->whereDate('created_at', today())->sum('amount'),
            'mtd'               => (clone $paidBase())->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
            'mrr'               => Subscription::where('status', 'active')->sum('amount'),
            'subs_active'       => Subscription::where('status', 'active')->count(),
            'subs_pastdue'      => Subscription::whereIn('status', ['past_due', 'at_risk'])->count(),
            'webhooks'          => WebhookEvent::count(),
            'webhooks_today'    => WebhookEvent::whereDate('created_at', today())->count(),
            // Funding qualifier funnel
            'qual_total'        => Lead::where('meta->funnel', 'qualifier')->count(),
            'qual_ready'        => Lead::where('meta->funnel', 'qualifier')->where('qualified', true)->count(),
            'qual_prep'         => Lead::where('meta->funnel', 'qualifier')->where('qualified', false)->count(),
            'qual_today'        => Lead::where('meta->funnel', 'qualifier')->whereDate('created_at', today())->count(),
        ];

        // Category cards. Every lead now flows through the qualifier, so the
        // only live types are funding-ready + funding-prep (credit repair),
        // alongside paid clients.
        $cats = [
            [
                'label' => 'Paid Credit Repair Clients',
                'count' => Client::where('service', 'credit-repair')->count(),
                'today' => Client::where('service', 'credit-repair')->whereDate('created_at', today())->count(),
                'new'   => Client::where('service', 'credit-repair')->where('status', 'active')->count(),
                'newLabel' => 'active',
                'url'   => route('admin.clients'),
                'gold'  => true,
            ],
            [
                'label' => 'Funding Ready',
                'count' => Lead::where('type', 'funding')->count(),
                'today' => Lead::where('type', 'funding')->whereDate('created_at', today())->count(),
                'new'   => Lead::where('type', 'funding')->where('status', 'new')->count(),
                'newLabel' => 'new',
                'url'   => route('admin.leads.type', 'funding'),
                'gold'  => false,
            ],
            [
                'label' => 'Credit Repair Ready',
                'count' => Lead::where('type', 'credit-repair')->count(),
                'today' => Lead::where('type', 'credit-repair')->whereDate('created_at', today())->count(),
                'new'   => Lead::where('type', 'credit-repair')->where('status', 'new')->count(),
                'newLabel' => 'new',
                'url'   => route('admin.leads.type', 'credit-repair'),
                'gold'  => false,
            ],
        ];

        $latestPayments = $paidBase()->latest()->limit(6)->get();
        $latestWebhooks = WebhookEvent::latest()->limit(6)->get();
        $recentLeads    = Lead::latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'cats', 'latestPayments', 'latestWebhooks', 'recentLeads'));
    }

    /** Global search across leads, clients and payments. */
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $leads = $clients = $payments = collect();
        if ($q !== '') {
            $like = "%{$q}%";
            $leads = Lead::where(fn ($x) => $x->where('name', 'like', $like)->orWhere('email', 'like', $like)->orWhere('phone', 'like', $like))->latest()->limit(50)->get();
            $clients = Client::where(fn ($x) => $x->where('first_name', 'like', $like)->orWhere('last_name', 'like', $like)->orWhere('email', 'like', $like)->orWhere('phone', 'like', $like))->latest()->limit(50)->get();
            $payments = Payment::where(fn ($x) => $x->where('name', 'like', $like)->orWhere('email', 'like', $like))->latest()->limit(50)->get();
        }

        return view('admin.search', compact('q', 'leads', 'clients', 'payments'));
    }

    /** Universal leads view + per-type filter. */
    public function leads(Request $request, string $type = 'all')
    {
        $query = Lead::query()->latest();

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($search = $request->query('q')) {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%"));
        }

        $leads = $query->paginate(25)->withQueryString();
        $title = $type === 'all' ? 'All Leads' : (Lead::TYPES[$type] ?? ucfirst($type) . ' Leads');

        return view('admin.leads', compact('leads', 'type', 'title'));
    }

    /** Full detail for a single lead (any form type). */
    public function leadShow(Lead $lead)
    {
        return view('admin.lead-show', compact('lead'));
    }

    /** Update a lead's pipeline status / admin notes. */
    public function leadUpdateStatus(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status'      => ['required', 'in:' . implode(',', Lead::STATUSES)],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $lead->update($data);

        return back()->with('ok', 'Lead updated.');
    }

    /** All payments. */
    public function payments(Request $request)
    {
        $payments = Payment::query()->latest()->paginate(25)->withQueryString();

        return view('admin.payments', compact('payments'));
    }

    /** Paid credit repair clients (onboarded). */
    public function clients(Request $request)
    {
        $clients = Client::query()
            ->where('service', 'credit-repair')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.clients', ['clients' => $clients, 'title' => 'Paid Credit Repair Clients']);
    }

    public function clientShow(Client $client)
    {
        return view('admin.client-show', compact('client'));
    }

    /** All subscriptions. */
    public function subscriptions()
    {
        $subscriptions = Subscription::query()->latest()->paginate(25);

        return view('admin.subscriptions', [
            'subscriptions' => $subscriptions,
            'title'         => 'Subscriptions',
            'atRisk'        => false,
        ]);
    }

    /** Subscriptions at risk (failed payments / past due). */
    public function subscriptionsAtRisk()
    {
        $subscriptions = Subscription::query()
            ->where(fn ($q) => $q->whereIn('status', ['at_risk', 'past_due'])->orWhere('failed_payments', '>', 0))
            ->latest()
            ->paginate(25);

        return view('admin.subscriptions', [
            'subscriptions' => $subscriptions,
            'title'         => 'Subscriptions At Risk',
            'atRisk'        => true,
        ]);
    }
}
