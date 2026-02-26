// Import Chart.js
import {
  Chart, LineController, LineElement, Filler, PointElement, LinearScale, TimeScale, Tooltip,
} from 'chart.js';
import 'chartjs-adapter-moment';
import { chartAreaGradient } from '../app';
import moment from 'moment';

// Import utilities
import { tailwindConfig, formatValue, hexToRGB } from '../utils';

Chart.register(LineController, LineElement, Filler, PointElement, LinearScale, TimeScale, Tooltip);

const dashboardCard02 = () => {
  const ctx = document.getElementById('dashboard-card-02');
  if (!ctx) return;

  const darkMode = localStorage.getItem('dark-mode') === 'true';

  const tooltipBodyColor = {
    light: '#6B7280',
    dark: '#9CA3AF'
  };

  const tooltipBgColor = {
    light: '#ffffff',
    dark: '#374151'
  };

  const tooltipBorderColor = {
    light: '#E5E7EB',
    dark: '#4B5563'
  };  

  fetch('/json-data-feed?datatype=2')
    .then(a => {
      return a.json();
    })
    .then(result => {

      const dataset1 = result.data;
      // Removed dataset2 since it's not needed and not showing

      const chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: result.labels,
          datasets: [
            // Main violet line (restored to original design)
            {
              data: dataset1,
              fill: true,
              backgroundColor: function(context) {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                return chartAreaGradient(ctx, chartArea, [
                  { stop: 0, color: `rgba(${hexToRGB(tailwindConfig().theme.colors.violet[500])}, 0)` },
                  { stop: 1, color: `rgba(${hexToRGB(tailwindConfig().theme.colors.violet[500])}, 0.2)` }
                ]);
              },
              borderColor: tailwindConfig().theme.colors.violet[500],
              borderWidth: 2,
              pointRadius: 0,
              pointHoverRadius: 3,
              pointBackgroundColor: tailwindConfig().theme.colors.violet[500],
              pointHoverBackgroundColor: tailwindConfig().theme.colors.violet[500],
              pointBorderWidth: 0,
              pointHoverBorderWidth: 0,
              clip: 20,
              tension: 0.2
            }
          ],
        },
        options: {
          layout: {
            padding: 20,
          },
          scales: {
            y: {
              display: false,
              beginAtZero: true,
            },
            x: {
              type: 'category', // Changed from 'time' to 'category' for years
              display: false,
            },
          },
          plugins: {
            tooltip: {
              callbacks: {
                title: (context) => {
                  const date = context[0].label;
                  return `Date Created: ${moment(date).format('MMM YYYY')}`;
                },
                label: (context) => {
                  return `Users: ${context.parsed.y}`;
                },
                footer: (context) => {
                  if (context.length > 0) {
                    const date = context[0].label;
                    const count = context[0].parsed.y;
                    const formattedDate = moment(date).format('MMM YYYY');
                    if (count === 1) {
                      return `On ${formattedDate} - 1 user created`;
                    } else {
                      return `On ${formattedDate} - ${count} users created`;
                    }
                  }
                  return '';
                }
              },
              titleFont: {
                size: 14,
                weight: 'bold'
              },
              bodyFont: {
                size: 12
              },
              footerFont: {
                size: 10,
                style: 'italic'
              },
              titleColor: darkMode ? '#ffffff' : '#1f2937',
              bodyColor: darkMode ? tooltipBodyColor.dark : tooltipBodyColor.light,
              footerColor: darkMode ? '#9CA3AF' : '#6B7280', // Add footer color
              backgroundColor: darkMode ? tooltipBgColor.dark : tooltipBgColor.light,
              borderColor: darkMode ? tooltipBorderColor.dark : tooltipBorderColor.light,
              borderWidth: 1,
              cornerRadius: 6,
              displayColors: true, // Show colored squares next to dataset labels
              multiKeyBackground: '#fff',
            },
            legend: {
              display: false,
            },
          },
          interaction: {
            intersect: false,
            mode: 'nearest',
          },
          maintainAspectRatio: false,
        },
      });
      
      document.addEventListener('darkMode', (e) => {
        const { mode } = e.detail;
        if (mode === 'on') {
          chart.options.plugins.tooltip.titleColor = '#ffffff';
          chart.options.plugins.tooltip.bodyColor = tooltipBodyColor.dark;
          chart.options.plugins.tooltip.footerColor = '#9CA3AF';
          chart.options.plugins.tooltip.backgroundColor = tooltipBgColor.dark;
          chart.options.plugins.tooltip.borderColor = tooltipBorderColor.dark;
        } else {
          chart.options.plugins.tooltip.titleColor = '#1f2937';
          chart.options.plugins.tooltip.bodyColor = tooltipBodyColor.light;
          chart.options.plugins.tooltip.footerColor = '#6B7280';
          chart.options.plugins.tooltip.backgroundColor = tooltipBgColor.light;
          chart.options.plugins.tooltip.borderColor = tooltipBorderColor.light;
        }
        chart.update('none');
      });      
    });
};

export default dashboardCard02;