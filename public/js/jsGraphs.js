
  function showGraphEx()
  {
      {
          $.post("/Balance/getBalanceData",
          function (data)
          {alert('pipi');

              console.log(data);
              alert(data);
               var name = [];
              var amount = [];
              for (var i in data) {
                  name.push(data[i].expenseName);
                  amount.push(data[i].expenseAmount);
              }
              var chartdata = {
                  labels: name,
                  datasets: [
                      {
                          backgroundColor: ["#92a8d1", "#034f84","#3cba9f","#e8c3b9","#c45850", "#f7786b", "#c94c4c", "#50394c", "#618685", "#4040a1", "#36486b", "#d64161", "#ff7b25", "#feb236", "#6b5b95", "#e3eaa7", "#b5e7a0", "#86af49", "#405d27", "#dac292", "#b9936c"],
                          hoverBackgroundColor: '#CCCCCC',
                          hoverBorderColor: '#666666',
                          data: amount
                      }
                  ]
              };
              var graphTarget = $("#myChart");
              var barGraph = new Chart(graphTarget, {
                  type: 'doughnut',
                  data: chartdata,
                  options: {
                        title:
                            {
                              display: true,
                              text: 'Procentowy udział wydatków'
                            }
                          }
              });
          });
      }
  }
  function showGraphIn(data)
  {
      {

          function (data)
          {
              
               var name = [];
              var amount = [];
              for (var i in data) {
                  name.push(data[i].name);
                  amount.push(data[i].amount);
              }
              var chartdata = {
                  labels: name,
                  datasets: [
                      {
                          backgroundColor: ["#d64161", "#ff7b25", "#feb236", "#6b5b95", "#e3eaa7", "#b5e7a0", "#86af49", "#405d27", "#dac292", "#b9936c", "#92a8d1", "#034f84","#3cba9f","#e8c3b9","#c45850", "#f7786b", "#c94c4c", "#50394c", "#618685", "#4040a1", "#36486b"],
                          hoverBackgroundColor: '#CCCCCC',
                          hoverBorderColor: '#666666',
                          data: amount
                      }
                  ]
              };
              var graphTarget = $("#myChart2");
              var barGraph = new Chart(graphTarget, {
                  type: 'doughnut',
                  data: chartdata,
  								options: {
  											title:
  													{
  														display: true,
  														text: 'Procentowy udział przychodów'
  													}
  												}
              });
          });
      }
  }
