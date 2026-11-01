import { Chart } from 'chart.js';

Chart.defaults.font.family = '"Inter", sans-serif';
Chart.defaults.font.weight = 500;
Chart.defaults.plugins.tooltip.borderWidth = 1;
Chart.defaults.plugins.tooltip.displayColors = false;
Chart.defaults.plugins.tooltip.mode = 'nearest';
Chart.defaults.plugins.tooltip.intersect = false;
Chart.defaults.plugins.tooltip.position = 'nearest';
Chart.defaults.plugins.tooltip.caretSize = 0;
Chart.defaults.plugins.tooltip.caretPadding = 20;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.padding = 8;

Chart.register({
  id: 'chartAreaPlugin',
  beforeDraw: (chart) => {
    if (!chart.config.options.chartArea?.backgroundColor) return;

    const ctx = chart.canvas.getContext('2d');
    const { chartArea } = chart;
    ctx.save();
    ctx.fillStyle = chart.config.options.chartArea.backgroundColor;
    ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
    ctx.restore();
  },
});

export const chartAreaGradient = (ctx, chartArea, colorStops) => {
  if (!ctx || !chartArea || !colorStops?.length) return 'transparent';

  const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
  colorStops.forEach(({ stop, color }) => gradient.addColorStop(stop, color));
  return gradient;
};
