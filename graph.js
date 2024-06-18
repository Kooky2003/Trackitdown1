
async function fetchData() {
  try {
    const response = await fetch('datafetch.php');
    const data = await response.json();

    processData(data);
  } catch (error) {
    console.error('Error fetching data:', error.message);
  }
}

function processData(data) {
  const chartData = {
    labels: data.map(item => item.taskname),
    data: data.map(item => item.timetaken),
  };

  createDoughnutChart(chartData);
  populateUl(chartData);
}

function createDoughnutChart(chartData) {
  const myChart = document.querySelector(".my-chart");

  new Chart(myChart, {
    type: "doughnut",
    data: {
      labels: chartData.labels,
      datasets: [
        {
          label: "Time Spent",
          data: chartData.data,
        },
      ],
    },
    options: {
      borderWidth: 10,
      borderRadius: 2,
      hoverBorderWidth: 0,
      plugins: {
        // legend: {
        //   display: false,
        // },
      },
      // tooltips: {
      //   callbacks: {
      //     label: (tooltipItem, data) => {
      //       // const label = data.labels[tooltipItem.index];
      //       // const value = data.datasets[0].data[tooltipItem.index];
      //       // return `${label}: ${value} sec`;
      //       return'';
      //     },
      //   },
      // },
    },
  });
}

const ul = document.querySelector(".programming-stats .details ul");

const populateUl = (chartData) => {
  chartData.labels.forEach((label, i) => {
    let li = document.createElement("li");
    li.textContent = `${label}: ${chartData.data[i]} sec`;
    ul.appendChild(li);
  });
}

fetchData();
