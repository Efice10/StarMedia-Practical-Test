@extends('layouts.app')

@php($title = 'Analytics Dashboard')

@push('styles')
    @vite('resources/css/pages/dashboard.css')
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="page-header">
            <h2>Analytics Dashboard</h2>
            <p>Track and analyze your social media engagement in real-time</p>
        </div>

        <div class="filters">
            <h3><i class="fas fa-sliders-h"></i> Filters</h3>
            <div class="filter-row">
                <div class="filter-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate">
                </div>
                <div class="filter-group">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate">
                </div>
                <div class="filter-group">
                    <label for="platformFilter">Platform</label>
                    <select id="platformFilter">
                        <option value="">All Platforms</option>
                    </select>
                </div>
                <div class="filter-group">
                    <button class="btn-filter" onclick="loadDashboard()">
                        <i class="fas fa-search"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-share-alt icon"></i>
                <h3>Total Shares</h3>
                <div class="value" id="totalShares">0</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-link icon"></i>
                <h3>Unique URLs</h3>
                <div class="value" id="uniqueUrls">0</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-crown icon"></i>
                <h3>Top Platform</h3>
                <div class="value value-lg" id="popularPlatform">-</div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <h3><i class="fas fa-chart-pie"></i> Platform Distribution</h3>
                <canvas id="platformChart"></canvas>
            </div>
            <div class="chart-card">
                <h3><i class="fas fa-chart-area"></i> Engagement Timeline</h3>
                <canvas id="timeChart"></canvas>
            </div>
        </div>

        <div class="table-card">
            <h3><i class="fas fa-trophy"></i> Top Performing URLs</h3>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>URL</th>
                        <th>Page Title</th>
                        <th class="td-center">Shares</th>
                    </tr>
                </thead>
                <tbody id="topUrlsTable">
                    <tr>
                        <td colspan="4" class="loading">Loading data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const API_URL = '/api';
    let authToken = localStorage.getItem('authToken');
    let platformChart, timeChart;
    if (!authToken) { window.location.href = '/login'; }
    async function fetchAPI(endpoint, options = {}) {
        const response = await fetch(`${API_URL}${endpoint}`, { ...options, headers: { 'Authorization': `Bearer ${authToken}`, 'Accept':'application/json','Content-Type':'application/json', ...(options.headers||{}) }});
        if (response.status === 401) { localStorage.removeItem('authToken'); localStorage.removeItem('userData'); window.location.href='/login'; return; }
        return await response.json();
    }
    function loadUserInfo() { const userData = JSON.parse(localStorage.getItem('userData')); if (userData) { /* layout already shows server user if authenticated */ } }
    async function loadPlatforms() { const response = await fetch(`${API_URL}/social-shares/platforms`); const data = await response.json(); if (data.success) { const select = document.getElementById('platformFilter'); data.data.platforms.forEach(p=>{ const o=document.createElement('option'); o.value=p.id; o.textContent=p.display_name; select.appendChild(o);}); }}
    async function loadDashboard() { const startDate=document.getElementById('startDate').value; const endDate=document.getElementById('endDate').value; const platformId=document.getElementById('platformFilter').value; const qp=new URLSearchParams(); if(startDate) qp.append('start_date',startDate); if(endDate) qp.append('end_date',endDate); if(platformId) qp.append('platform_id',platformId); const data=await fetchAPI(`/analytics/dashboard?${qp}`); if(data?.success){ updateStats(data.data.stats); updateCharts(data.data); updateTopUrls(data.data.top_urls);} }
    function updateStats(stats){ document.getElementById('totalShares').textContent=stats.total_shares.toLocaleString(); document.getElementById('uniqueUrls').textContent=stats.unique_urls.toLocaleString(); document.getElementById('popularPlatform').textContent = stats.most_popular_platform ? `${stats.most_popular_platform.name} (${stats.most_popular_platform.count})` : 'No data'; }
    function updateCharts(data){
        const platformData=data.shares_by_platform; const timeData=data.shares_by_date;
        if(platformChart) platformChart.destroy(); if(timeChart) timeChart.destroy();
        const platformCtx=document.getElementById('platformChart').getContext('2d');
        platformChart=new Chart(platformCtx,{
            type:'doughnut',
            data:{
                labels:platformData.map(p=>p.display_name),
                datasets:[{
                    data:platformData.map(p=>p.count),
                    backgroundColor:platformData.map(p=>p.color),
                    borderWidth:2,
                    borderColor:'rgba(255,255,255,0.6)',
                    hoverOffset:6
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio:true,
                plugins:{
                    legend:{ position:'bottom', labels:{ color:'#0f172a', padding:16, font:{ size:12, family:'Inter', weight:'500'} } },
                    tooltip:{ backgroundColor:'#ffffff', titleColor:'#0f172a', bodyColor:'#475569', borderColor:'#e2e8f0', borderWidth:1, padding:10, cornerRadius:6, titleFont:{ size:12, weight:'600'}, bodyFont:{ size:12 } }
                },
                cutout:'58%'
            }
        });
        const timeCtx=document.getElementById('timeChart').getContext('2d');
        timeChart=new Chart(timeCtx,{
            type:'line',
            data:{
                labels:timeData.map(d=>d.date),
                datasets:[{
                    label:'Shares',
                    data:timeData.map(d=>d.count),
                    borderColor:'#3b82f6',
                    backgroundColor:function(ctx){ const g=ctx.chart.ctx.createLinearGradient(0,0,0,300); g.addColorStop(0,'rgba(59,130,246,0.08)'); g.addColorStop(1,'rgba(59,130,246,0)'); return g; },
                    tension:0.25,
                    fill:true,
                    borderWidth:2,
                    pointRadius:3,
                    pointHoverRadius:5,
                    pointBackgroundColor:'#3b82f6',
                    pointBorderColor:'#ffffff',
                    pointBorderWidth:2,
                    pointHoverBorderWidth:2
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio:true,
                plugins:{
                    legend:{ display:false },
                    tooltip:{ backgroundColor:'#ffffff', titleColor:'#0f172a', bodyColor:'#475569', borderColor:'#e2e8f0', borderWidth:1, padding:10, cornerRadius:6, titleFont:{ size:12, weight:'600'}, bodyFont:{ size:12 } }
                },
                scales:{
                    y:{ beginAtZero:true, grid:{ color:'#f1f5f9', drawBorder:false }, ticks:{ color:'#64748b', font:{ size:11, family:'Inter', weight:'500'}, padding:6 } },
                    x:{ grid:{ color:'#f8fafc', drawBorder:false }, ticks:{ color:'#64748b', font:{ size:11, family:'Inter', weight:'500'}, padding:6 } }
                }
            }
        });
    }
    function updateTopUrls(urls){ const tbody=document.getElementById('topUrlsTable'); tbody.innerHTML=''; if(!urls.length){ tbody.innerHTML='<tr><td colspan="4" class="loading">No data available</td></tr>'; return;} urls.forEach((u,i)=>{ const row=document.createElement('tr'); row.innerHTML=`<td><span class=\"rank-badge\">${i+1}</span></td><td class=\"td-tight\">${u.url}</td><td class=\"td-strong\">${u.page_title||'N/A'}</td><td class=\"td-center\"><strong class=\"share-count\">${u.share_count}</strong></td>`; tbody.appendChild(row); }); }
    document.addEventListener('DOMContentLoaded',()=>{ loadUserInfo(); loadPlatforms(); loadDashboard(); });
</script>
@endpush
