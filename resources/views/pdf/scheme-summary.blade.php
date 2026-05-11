<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheme Summary — {{ $scheme->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; line-height: 1.5; }
        .header { background: #15803d; color: white; padding: 16px 20px; }
        .header-title { font-size: 18px; font-weight: 700; }
        .header-sub   { font-size: 9px; opacity: 0.8; margin-bottom: 6px; }
        .header-meta  { font-size: 12px; font-weight: 600; }

        .info-bar { background: #f0fdf4; border-bottom: 1px solid #bbf7d0; padding: 10px 20px; }

        .cards { display: table; width: 100%; padding: 12px 20px; background: #fff; border-bottom: 1px solid #e5e7eb; }
        .card  { display: table-cell; text-align: center; padding: 8px; }
        .card-value { font-size: 18px; font-weight: 700; }
        .card-label { font-size: 9px; color: #6b7280; margin-top: 1px; }
        .c-gray   { color: #374151; }
        .c-yellow { color: #a16207; }
        .c-green  { color: #15803d; }
        .c-red    { color: #b91c1c; }
        .c-blue   { color: #1d4ed8; }

        .section { padding: 10px 20px; }
        .section-title { font-size: 11px; font-weight: 700; color: #15803d; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f9fafb; padding: 5px 8px; text-align: left; font-size: 9px; font-weight: 700; color: #374151; text-transform: uppercase; border-bottom: 2px solid #d1d5db; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 9999px; font-size: 9px; font-weight: 600; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .text-right { text-align: right; }
        .total-row td { font-weight: 700; background: #f0fdf4 !important; color: #15803d; }
        .footer { padding: 8px 20px; background: #f9fafb; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-sub">FarmTech MIS — Agriculture Management Information System</div>
        <div class="header-title">{{ $scheme->name }}</div>
        <div style="font-size:10px; opacity:0.8; margin-top:4px;">
            {{ $scheme->category }} &nbsp;|&nbsp;
            {{ $scheme->ministry ?? 'Government Scheme' }} &nbsp;|&nbsp;
            Status: {{ $scheme->is_active ? 'Active' : 'Inactive' }} &nbsp;|&nbsp;
            Generated: {{ date('d M Y') }}
        </div>
    </div>

    @if($scheme->benefit_amount || $scheme->end_date)
    <div class="info-bar">
        <span>
            @if($scheme->benefit_amount) &#8377;{{ number_format($scheme->benefit_amount) }} max benefit &nbsp;|&nbsp; @endif
            @if($scheme->end_date) Valid until: {{ $scheme->end_date->format('d M Y') }} &nbsp;|&nbsp; @endif
            @if($scheme->max_beneficiaries) Max beneficiaries: {{ $scheme->max_beneficiaries }} @endif
        </span>
    </div>
    @endif

    {{-- Stats Row --}}
    <div class="cards">
        <div class="card"><div class="card-value c-gray">{{ $stats['total'] }}</div><div class="card-label">Total Applied</div></div>
        <div class="card"><div class="card-value c-yellow">{{ $stats['pending'] }}</div><div class="card-label">Pending</div></div>
        <div class="card"><div class="card-value c-green">{{ $stats['approved'] }}</div><div class="card-label">Approved</div></div>
        <div class="card"><div class="card-value c-red">{{ $stats['rejected'] }}</div><div class="card-label">Rejected</div></div>
        <div class="card">
            <div class="card-value c-blue">&#8377;{{ number_format($stats['disbursed']) }}</div>
            <div class="card-label">Total Disbursed</div>
        </div>
        <div class="card">
            <div class="card-value c-green">
                {{ $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0 }}%
            </div>
            <div class="card-label">Approval Rate</div>
        </div>
    </div>

    {{-- Applications Table --}}
    <div class="section" style="margin-top:4px;">
        <div class="section-title">All Applications</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Farmer Name</th>
                    <th>Village / District</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th class="text-right">Benefit (&#8377;)</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $i => $app)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $app->farmer?->user?->name }}</td>
                    <td>{{ $app->farmer?->village }}, {{ $app->farmer?->district }}</td>
                    <td>{{ $app->applied_date?->format('d M Y') }}</td>
                    <td>
                        <span class="badge
                            {{ $app->status === 'approved' ? 'badge-green' :
                               ($app->status === 'rejected' ? 'badge-red' : 'badge-yellow') }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        {{ $app->subsidy_amount ? '&#8377;' . number_format($app->subsidy_amount) : '—' }}
                    </td>
                    <td style="max-width:150px; word-break:break-word;">{{ Str::limit($app->remarks, 60) ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:#9ca3af; padding:16px;">No applications yet.</td></tr>
                @endforelse
                @if($applications->isNotEmpty())
                <tr class="total-row">
                    <td colspan="5" style="text-align:right;">Total Disbursed</td>
                    <td class="text-right">&#8377;{{ number_format($stats['disbursed']) }}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>FarmTech MIS</strong> — This document is system-generated. &nbsp;|&nbsp; Printed on {{ date('d M Y, h:i A') }}
    </div>

</body>
</html>
