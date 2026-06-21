<section class="section-head">
    <div>
        <span class="eyebrow">管理後台</span>
        <h1>營運儀表板</h1>
    </div>
</section>

<section class="dashboard-grid">
    <div class="metric-card"><strong><?= (int) $stats['total_orders'] ?></strong><span>總訂單數</span></div>
    <div class="metric-card"><strong><?= e(money((float) $stats['total_revenue'])) ?></strong><span>累計營收</span></div>
    <div class="metric-card"><strong><?= (int) $stats['pending_orders'] ?></strong><span>待處理訂單</span></div>
    <div class="metric-card"><strong><?= (int) $stats['active_members'] ?></strong><span>啟用會員</span></div>
</section>

<section class="chart-grid">
    <div class="chart-card">
        <h2>月營收趨勢</h2>
        <canvas id="monthlyChart"></canvas>
    </div>
    <div class="chart-card">
        <h2>熱門商品排行</h2>
        <canvas id="topChart"></canvas>
    </div>
</section>

<script>
const chartData = <?= $chartData ?>;
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: chartData.monthlyLabels,
        datasets: [{
            label: '營收',
            data: chartData.monthlyValues,
            borderColor: '#b85c38',
            backgroundColor: 'rgba(184, 92, 56, 0.15)',
            fill: true,
            tension: 0.35,
        }],
    },
});

new Chart(document.getElementById('topChart'), {
    type: 'bar',
    data: {
        labels: chartData.topLabels,
        datasets: [{
            label: '銷量',
            data: chartData.topValues,
            backgroundColor: '#efc29f',
        }],
    },
});
</script>