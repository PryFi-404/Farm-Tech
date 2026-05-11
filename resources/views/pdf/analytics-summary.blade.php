<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FarmTech Analytics Summary</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; line-height: 1.5; }
        .header { background: #15803d; color: white; padding: 18px 24px; }
        .header-title { font-size: 20px; font-weight: 700; }
        .header-date  { font-size: 10px; opacity: 0.8; margin-top: 4px; }

        .section { padding: 12px 24px; border-bottom: 1px solid #e5e7eb; }
        .section-title { font-size: 11px; font-weight: 700; color: #15803d; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; }

        .cards-grid { display: table; width: 100%; border-collapse: separate; border-spacing: 6px; }
        .card { display: table-cell; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px; text-align: center; width: 12%; }
        .card-value { font-size: 15px; font-weight: 700; color: #15803d; }
        .card-label { font-size: 8px; color: #6b7280; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f9fafb; padding: 6px 8px; text-align: left; font-size: 9px; font-weight: 700; color: #374151; text-transform: uppercase; border-bottom: 2px solid #d1d5db; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-green  { display: inline-block; padding: 1px 6px; border-radius: 9999px; font-size: 9px; font-weight: 600; background: #dcfce7; color: #15803d; }
        .rank-gold   { background: #fbbf24; color: white; border-radius: 50%; width: 18px; height: 18px; display: inline-block; text-align: center; font-weight: 700; }
        .rank-silver { background: #d1d5db; color: #374151; border-radius: 50%; width: 18px; height: 18px; display: inline-block; text-align: center; font-weight: 700; }
        .rank-bronze { background: #92400e; color: white; border-radius: 50%; width: 18px; height: 18px; display: inline-block; text-align: center; font-weight: 700; }
        .rank-plain  { background: #e5e7eb; color: #374151; border-radius: 50%; width: 18px; height: 18px; display: inline-block; text-align: center; font-weight: 700; }
        .footer { padding: 10px 24px; text-align: center; font-size: 9px; color: #9ca3af; background: #f9fafb; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title">FarmTech MIS — Analytics Summary Report</div>
        <div class="header-date">Generated on {{ date('d M Y, h:i A') }} &nbsp;|&nbsp; Agriculture Management Information System</div>
    </div>

    {{-- Overview Stats --}}
    <div class="section">
        <div class="section-title">Overview at a Glance</div>
        <div class="cards-grid">
            <div class="card">
                <div class="card-value">{{ number_format($summary['total_farmers']) }}</div>
                <div class="card-label">Farmers</div>
            </div>
            <div class="card">
                <div class="card-value">{{ number_format($summary['total_land_acres'], 1) }}</div>
                <div class="card-label">Land Acres</div>
            </div>
            <div class="card">
                <div class="card-value">{{ number_format($summary['total_production']) }}</div>
                <div class="card-label">Prod. (kg)</div>
            </div>
            <div class="card">
                <div class="card-value">{{ $summary['total_schemes'] }}</div>
                <div class="card-label">Schemes</div>
            </div>
            <div class="card">
                <div class="card-value">{{ $summary['total_applied'] }}</div>
                <div class="card-label">Applications</div>
            </div>
            <div class="card">
                <div class="card-value">{{ $summary['total_approved'] }}</div>
                <div class="card-label">Approved</div>
            </div>
            <div class="card" style="background:#dbeafe; border-color:#93c5fd;">
                <div class="card-value" style="color:#1d4ed8;">&#8377;{{ number_format($summary['total_disbursed']) }}</div>
                <div class="card-label">Disbursed</div>
            </div>
        </div>
    </div>

    {{-- Farmer Distribution --}}
    <div class="section">
        <div class="section-title">Farmer Distribution by District</div>
        <table>
            <thead>
                <tr>
                    <th>District</th>
                    <th class="text-right">Farmers</th>
                    <th>Share</th>
                </tr>
            </thead>
            <tbody>
                @php $totalFarmers = $farmersByDistrict->sum('count') ?: 1; @endphp
                @foreach($farmersByDistrict as $row)
                <tr>
                    <td style="font-weight:600;">{{ $row->district }}</td>
                    <td class="text-right">{{ $row->count }}</td>
                    <td>
                        <div style="background:#e5e7eb; border-radius:4px; height:8px; width:150px;">
                            <div style="background:#15803d; border-radius:4px; height:8px; width:{{ round(($row->count/$totalFarmers)*150) }}px;"></div>
                        </div>
                        <span style="font-size:9px; color:#6b7280;">{{ round(($row->count/$totalFarmers)*100) }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Scheme Performance --}}
    <div class="section">
        <div class="section-title">Scheme Performance</div>
        <table>
            <thead>
                <tr>
                    <th>Scheme Name</th>
                    <th>Category</th>
                    <th class="text-center">Applied</th>
                    <th class="text-center">Approved</th>
                    <th class="text-center">Rate</th>
                    <th class="text-right">Disbursed (&#8377;)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schemePerformance as $s)
                <tr>
                    <td style="font-weight:600;">{{ Str::limit($s->name, 40) }}</td>
                    <td><span class="badge-green">{{ $s->category }}</span></td>
                    <td class="text-center">{{ $s->applications_count }}</td>
                    <td class="text-center" style="color:#15803d; font-weight:700;">{{ $s->approved_count }}</td>
                    <td class="text-center">
                        {{ $s->applications_count > 0 ? round(($s->approved_count / $s->applications_count) * 100) : 0 }}%
                    </td>
                    <td class="text-right" style="color:#15803d; font-weight:700;">
                        {{ $s->total_disbursed ? '&#8377;' . number_format($s->total_disbursed) : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Top Beneficiaries --}}
    <div class="section">
        <div class="section-title">Top Beneficiary Farmers</div>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Farmer Name</th>
                    <th>Village / District</th>
                    <th class="text-center">Schemes</th>
                    <th class="text-right">Total Benefit (&#8377;)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topBeneficiaries as $i => $b)
                <tr>
                    <td class="text-center">
                        @if($i === 0) <span class="rank-gold">1</span>
                        @elseif($i === 1) <span class="rank-silver">2</span>
                        @elseif($i === 2) <span class="rank-bronze">3</span>
                        @else <span class="rank-plain">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td style="font-weight:600;">{{ $b->farmer?->user?->name }}</td>
                    <td>{{ $b->farmer?->village }}, {{ $b->farmer?->district }}</td>
                    <td class="text-center">{{ $b->schemes_count }}</td>
                    <td class="text-right" style="color:#15803d; font-weight:700;">&#8377;{{ number_format($b->total_benefit) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>FarmTech MIS</strong> — Agriculture Management Information System &nbsp;|&nbsp;
        This is a system-generated analytics report. &nbsp;|&nbsp;
        {{ date('d M Y') }}
    </div>

</body>
</html>
