<?php

/*
|--------------------------------------------------------------------------
| Checkout plans  (single source of truth for pricing — front end & back end)
|--------------------------------------------------------------------------
| Every 6-month program can be paid two ways, chosen at checkout via `billing`:
|
|   billing = 'onetime'      -> charge `onetime` once, no subscription.
|   billing = 'subscription' -> charge `sub_setup` today, then an Authorize.Net
|                               ARB subscription of `sub_monthly` for `term`
|                               monthly occurrences.
|
| The pricing cards (home + pricing pages) and the checkout page all read these
| same values, so what the customer sees always equals what is charged.
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
        'name'        => 'Individual',
        'tagline'     => 'Single-person program',
        'blurb'       => 'A complete done-for-you program disputing inaccurate late payments, collections, charge-offs and inquiries across all 3 bureaus.',
        'onetime'     => 497.00,   // pay once — covers the full 6-month program
        'sub_setup'   => 189.00,   // due today to start the monthly plan
        'sub_monthly' => 99.00,    // per month
        'term'        => 6,        // number of monthly charges (6-month program)
        'service'     => 'credit-repair',
        'onboarding'  => true,
        'features'    => $coreFeatures,
    ],

    'aggressive' => [
        'name'        => 'Aggressive Attack',
        'tagline'     => 'Maximum, fastest results',
        'blurb'       => 'Our most aggressive program — priority multi-round dispute cycles plus score optimization and debt validation for the fastest possible turnaround.',
        'onetime'     => 697.00,
        'sub_setup'   => 129.00,
        'sub_monthly' => 249.00,
        'term'        => 6,
        'service'     => 'credit-repair',
        'onboarding'  => true,
        'features'    => array_merge($coreFeatures, [
            'Aggressive multi-round attacks',
            'Priority dispute cycles',
            'Score optimization plan',
            'Debt validation & priority support',
        ]),
    ],

    'couple' => [
        'name'        => 'Husband & Wife',
        'tagline'     => 'Two people, one plan',
        'blurb'       => 'Both credit profiles worked together with a shared dedicated strategist — full disputing across all 3 bureaus for two people.',
        'onetime'     => 797.00,
        'sub_setup'   => 297.00,
        'sub_monthly' => 149.99,
        'term'        => 6,
        'service'     => 'credit-repair',
        'onboarding'  => true,
        'features'    => array_merge([
            'Both credit profiles worked together',
            'All 3 bureaus covered for two people',
            'Unlimited disputes for both',
            'Shared dedicated strategist',
        ], $coreFeatures),
    ],

];
