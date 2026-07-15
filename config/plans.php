<?php

/*
|--------------------------------------------------------------------------
| Checkout plans
|--------------------------------------------------------------------------
| Keyed by slug used in /checkout/{plan}. `down` is charged immediately;
| `monthly` (if set) creates an Authorize.Net ARB recurring subscription.
*/

$coreFeatures = [
    'Personal information update',
    'Late payments challenged',
    'Collections challenged',
    'Charge-offs challenged',
    'Hard inquiries challenged',
    'Fast-tracked results in 30–90 days',
    '3-bureau dispute letters prepared on your behalf',
    'FCRA & FDCPA-based dispute strategy',
    'Credit-building guidance after disputes',
    'Email & SMS status updates',
    'Monthly progress reports + live score tracking',
    '24/7 client portal access',
];

return [

    'individual' => [
        'name'       => 'Individual',
        'tagline'    => 'Single-person program',
        'blurb'      => 'A complete done-for-you program disputing inaccurate late payments, collections, charge-offs and inquiries across all 3 bureaus.',
        'down'       => 497.00,
        'monthly'    => 99.00,
        'service'    => 'credit-repair',
        'onboarding' => true,
        'features'   => $coreFeatures,
    ],

    'aggressive' => [
        'name'       => 'Aggressive Attack',
        'tagline'    => 'Maximum, fastest results',
        'blurb'      => 'Our most aggressive program — priority multi-round dispute cycles plus score optimization and debt validation for the fastest possible turnaround.',
        'down'       => 997.00,
        // One-time payment — no monthly recurring.
        'service'    => 'credit-repair',
        'onboarding' => true,
        'features'   => array_merge($coreFeatures, [
            'Aggressive multi-round attacks',
            'Priority dispute cycles',
            'Score optimization plan',
            'Debt validation & priority support',
        ]),
    ],

    'couple' => [
        'name'       => 'Husband & Wife',
        'tagline'    => 'Two people, one plan',
        'blurb'      => 'Both credit profiles worked together with a shared dedicated strategist — full disputing across all 3 bureaus for two people.',
        'down'       => 897.00,
        // One-time payment — no monthly recurring.
        'service'    => 'credit-repair',
        'onboarding' => true,
        'features'   => array_merge([
            'Both credit profiles worked together',
            'All 3 bureaus covered for two people',
            'Unlimited disputes for both',
            'Shared dedicated strategist',
        ], $coreFeatures),
    ],

];
