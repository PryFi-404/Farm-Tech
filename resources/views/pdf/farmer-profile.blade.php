<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile — {{ $farmer->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }

        /* Header */
        .header { background: #15803d; color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: flex-start; }
        .header-title { font-size: 20px; font-weight: 700; margin-bottom: 2px; }
        .header-sub   { font-size: 10px; opacity: 0.8; }
        .header-right { text-align: right; font-size: 10px; opacity: 0.9; }

        /* Sections */
        .section { padding: 14px 24px; border-bottom: 1px solid #e5e7eb; }
        .section-title { font-size: 12px; font-weight: 700; color: #15803d; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; }

        /* Grid */
        .grid-2 { display: table; width: 100%; }
        .col     { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
        .col:last-child { padding-right: 0; }

        /* Field */
        .field { margin-bottom: 7px; }
        .field label { color: #6b7280; font-size: 9px; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 1px; }
        .field span  { font-weight: 600; color: #111827; }

        /* Summary Cards */
        .cards { display: table; width: 100%; border-collapse: separate; border-spacing: 8px; margin: -4px; }
        .card  { display: table-cell; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px; text-align: center; }
        .card-value { font-size: 16px; font-weight: 700; color: #15803d; }
        .card-label { font-size: 9px; color: #6b7280; margin-top: 2px; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th { background: #f9fafb; padding: 6px 8px; text-align: left; font-size: 9px; font-weight: 700; color: #374151; text-transform: uppercase; border-bottom: 2px solid #e5e7eb; }
        td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 9999px; font-size: 9px; font-weight: 600; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }

        /* Footer */
        .footer { padding: 10px 24px; background: #f9fafb; text-align: center; font-size: 9px; color: #9ca3af; }
        .text-right { text-align: right; }
        .text-green { color: #15803d; font-weight: 700; }
    </style>
</head>
<body>

    {{-- ── HEADER ──────────────────────────────────────────────────────── --}}
    <div class="header">
        <div>
            <div class="header-title">FarmTech MIS</div>
            <div class="header-sub">Agriculture Management Information System</div>
            <div style="margin-top:8px; font-size:14px; font-weight:600;">
                Farmer Profile Report
            </div>
        </div>
        <div class="header-right">
            <div>Generated: {{ date('d M Y, h:i A') }}</div>
            <div style="margin-top:4px;">Farmer ID: #{{ $farmer->id }}</div>
        </div>
    </div>

    {{-- ── SUMMARY CARDS ────────────────────────────────────────────────── --}}
    <div class="section">
        <div class="cards">
            <div class="card">
                <div class="card-value">{{ number_format($totalLandArea, 1) }}</div>
                <div class="card-label">Land Area (acres)</div>
            </div>
            <div class="card">
                <div class="card-value">{{ $farmer->lands->count() }}</div>
                <div class="card-label">Land Parcels</div>
            </div>
            <div class="card">
                <div class="card-value">{{ number_format($totalProduction) }}</div>
                <div class="card-label">Production (kg)</div>
            </div>
            <div class="card">
                <div class="card-value">&#8377;{{ number_format($totalRevenue) }}</div>
                <div class="card-label">Est. Revenue</div>
            </div>
            <div class="card">
                <div class="card-value" style="color:#1d4ed8;">&#8377;{{ number_format($totalBenefit) }}</div>
                <div class="card-label">Scheme Benefit</div>
            </div>
        </div>
    </div>

    {{-- ── PERSONAL INFO ────────────────────────────────────────────────── --}}
    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="grid-2">
            <div class="col">
                <div class="field"><label>Full Name</label><span>{{ $farmer->user->name }}</span></div>
                <div class="field"><label>Gender</label><span>{{ $farmer->gender ?? '—' }}</span></div>
                <div class="field"><label>Date of Birth</label><span>{{ $farmer->date_of_birth?->format('d M Y') ?? '—' }}</span></div>
                <div class="field"><label>Phone</label><span>{{ $farmer->phone }}</span></div>
                <div class="field"><label>Email</label><span>{{ $farmer->user->email }}</span></div>
            </div>
            <div class="col">
                <div class="field"><label>Aadhaar Number</label><span>****-****-{{ substr($farmer->aadhaar, -4) }}</span></div>
                <div class="field"><label>Voter ID</label><span>{{ $farmer->voter_id ?? '—' }}</span></div>
                <div class="field"><label>Farmer Category</label><span>{{ $farmer->farmer_category ?? '—' }}</span></div>
                <div class="field"><label>Education</label><span>{{ $farmer->education ?? '—' }}</span></div>
                <div class="field"><label>Annual Income</label><span>{{ $farmer->annual_income ? '&#8377;' . number_format($farmer->annual_income) : '—' }}</span></div>
            </div>
        </div>
    </div>

    {{-- ── ADDRESS ──────────────────────────────────────────────────────── --}}
    <div class="section">
        <div class="section-title">Location</div>
        <div class="grid-2">
            <div class="col">
                <div class="field"><label>Village</label><span>{{ $farmer->village }}</span></div>
                <div class="field"><label>Block</label><span>{{ $farmer->block ?? '—' }}</span></div>
            </div>
            <div class="col">
                <div class="field"><label>District</label><span>{{ $farmer->district }}</span></div>
                <div class="field"><label>State</label><span>{{ $farmer->state ?? 'Chhattisgarh' }}</span></div>
            </div>
        </div>
    </div>

    {{-- ── BANK INFO ────────────────────────────────────────────────────── --}}
    @if($farmer->bank_account || $farmer->ifsc)
    <div class="section">
        <div class="section-title">Bank Information</div>
        <div class="grid-2">
            <div class="col">
                <div class="field"><label>Bank Name</label><span>{{ $farmer->bank_name ?? '—' }}</span></div>
                <div class="field"><label>Account Number</label><span>****{{ substr($farmer->bank_account, -4) }}</span></div>
            </div>
            <div class="col">
                <div class="field"><label>IFSC Code</label><span>{{ $farmer->ifsc ?? '—' }}</span></div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── LAND RECORDS ─────────────────────────────────────────────────── --}}
    @if($farmer->lands->isNotEmpty())
    <div class="section">
        <div class="section-title">Land Records ({{ $farmer->lands->count() }} parcels — {{ number_format($totalLandArea, 2) }} total acres)</div>
        <table>
            <thead>
                <tr>
                    <th>Survey No.</th>
                    <th>Khasra No.</th>
                    <th>Area (acres)</th>
                    <th>Soil Type</th>
                    <th>Irrigation</th>
                    <th>Ownership</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmer->lands as $land)
                <tr>
                    <td>{{ $land->survey_number }}</td>
                    <td>{{ $land->khasra_number ?? '—' }}</td>
                    <td>{{ $land->area_acres }}</td>
                    <td>{{ $land->soil_type ?? '—' }}</td>
                    <td>{{ $land->irrigation_type ?? '—' }}</td>
                    <td><span class="badge badge-green">{{ $land->ownership_type }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── CROP HISTORY ─────────────────────────────────────────────────── --}}
    @if($farmer->cropHistories->isNotEmpty())
    <div class="section">
        <div class="section-title">Crop Production History</div>
        <table>
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Season</th>
                    <th>Year</th>
                    <th>Area (ac)</th>
                    <th>Production (kg)</th>
                    <th>Price/kg</th>
                    <th class="text-right">Est. Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmer->cropHistories->sortByDesc('year') as $ch)
                <tr>
                    <td>{{ $ch->crop?->name }}</td>
                    <td><span class="badge badge-blue">{{ $ch->season }}</span></td>
                    <td>{{ $ch->year }}</td>
                    <td>{{ $ch->area_used }}</td>
                    <td>{{ number_format($ch->production_kg) }}</td>
                    <td>&#8377;{{ $ch->selling_price }}</td>
                    <td class="text-right text-green">&#8377;{{ number_format($ch->production_kg * $ch->selling_price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── SCHEME APPLICATIONS ──────────────────────────────────────────── --}}
    @if($farmer->schemeApplications->isNotEmpty())
    <div class="section">
        <div class="section-title">Scheme Applications</div>
        <table>
            <thead>
                <tr>
                    <th>Scheme</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th class="text-right">Benefit (&#8377;)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmer->schemeApplications->sortByDesc('applied_date') as $app)
                <tr>
                    <td>{{ $app->scheme?->name }}</td>
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
                </tr>
                @endforeach
                <tr style="background:#f0fdf4;">
                    <td colspan="3" style="font-weight:700; color:#15803d;">Total Approved Benefit</td>
                    <td class="text-right text-green">&#8377;{{ number_format($totalBenefit) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── FOOTER ───────────────────────────────────────────────────────── --}}
    <div class="footer">
        <strong>FarmTech MIS</strong> — Agriculture Management Information System &nbsp;|&nbsp;
        This document is system-generated and does not require a signature. &nbsp;|&nbsp;
        Printed on {{ date('d M Y') }}
    </div>

</body>
</html>
