<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Akar Kuadrat API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dcdcdc; /* Light gray background */
            text-align: center;
            margin: 0;
            padding: 0;
        }
    
        h1 {
            background-color: #751c1c; /* Dark red background */
            color: #f0f0f0; /* White text */
            padding: 20px;
        }
    
        div {
            margin: 20px;
            padding: 10px;
            background-color: #f0f0f0; /* Light gray background */
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    
        label {
            display: block;
            margin-bottom: 10px;
        }
    
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%;
            margin-bottom: 10px;
        }
    
        select {
            padding: 10px;
            background-color: #751c1c; /* Dark red background */
            color: #f0f0f0; /* White text */
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 10%;
            margin-bottom: 10px;
            text-align: center;
        }
    
        button {
            background-color: #751c1c; /* Dark red background */
            color: #f0f0f0; /* White text */
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
    
        button:hover {
            background-color: #5a0527; /* Slightly lighter red on hover */
        }
    
        #result {
            margin-top: 20px;
            /* font-weight: bold; */
            background-color: #e6e6e6; /* Light gray background */
            border: 1px solid #ccc; /* Add a border for separation */
            padding: 15px; /* Increased padding for more space */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle box shadow */
            color: #333; /* Adjust text color for contrast */
            opacity: 0; /* Initially invisible */
            transition: opacity 0.3s ease; /* Smooth opacity transition */
        }

        #result.visible {
            font-weight: bold;
            opacity: 1; /* Make it visible with animation */
        }

    
        #history {
            margin-top: 20px;
        }
    
        .error {
            border: 1px solid red;
        }
    
        /* Styling for the data table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
    
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
    
        th {
            background-color: #751c1c; /* Dark red background */
            color: #f0f0f0; /* White text */
        }
    
        tr:hover {
            background-color: #f5f5f5;
        }

        #clear-history-button {
            background-color: #751c1c; /* Dark red background */
            color: #f0f0f0; /* White text */
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            margin-top: 10px;
        }

        #clear-history-button:hover {
            background-color: #5a0527; /* Slightly lighter red on hover */
        }
    </style>
    
</head>
<body>
    <h1>Hitung Akar Kuadrat API</h1>
    <div>
        <label for="number">Masukkan Angka:</label>
        <input type="number" id="number" step="any" placeholder="Angka yang ingin diakar kuadratkan">
    </div>
    <button onclick="calculateSquareRoot()">Hitung dengan API</button>
    <button onclick="calculateSquareRootWithSP()">Hitung dengan Stored Procedure</button>
    <button onclick="clearResult()">Clear</button>
    <div id="result"></div>
    <div id="history">
        <h3>Riwayat Input</h3>
        <!-- <button id="clear-history-button" onclick="clearHistory()">Clear History</button> -->
        <table id="history-table">
            <thead>
                <tr>
                    <th>Input</th>
                    <th>Hasil</th>
                    <th>Type</th>
                    <th>Execution Time</th>
                </tr>
            </thead>
            <tbody id="history-list"></tbody>
        </table>
    </div>

    <script>
        const URL = 'http://127.0.0.1:8000'
        fetchHistory();
        const historyList = document.getElementById('history-list');
        const historyTable = document.getElementById('history-table');
        const decimalPlacesSelect = document.getElementById('decimal-places');
        const numberInput = document.getElementById('number');

        // Calculate square root with stored procedure
        async function calculateSquareRootWithSP() {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = ''; // Clear previous results

            const number = parseFloat(numberInput.value);

            if (isNaN(number) || number < 0) {
                resultDiv.textContent = 'Masukkan angka positif yang valid.';
                numberInput.classList.add('error');
                return;
            } else {
                numberInput.classList.remove('error');
            }

            try {
                const response = await fetch(`${URL}/api/akar-kuadrat-sql/${number}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                const data = await response.json();
                if (data.error) {
                    resultDiv.textContent = data.error;
                } else {
                    const resultText = `Akar kuadrat dari ${number} adalah ${data.hasil}`;
                    resultDiv.textContent = resultText;

                    // Add to history
                    fetchHistory();
                
                    // Add the 'visible' class to show the result with animation
                    resultDiv.classList.add('visible');
                }
            } catch (error) {
                resultDiv.textContent = 'Terjadi kesalahan saat mengambil hasil.';
            }
        }



        // Calculate square root
        async function calculateSquareRoot() {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = ''; // Clear previous results

            const number = parseFloat(numberInput.value);

            if (isNaN(number) || number < 0) {
                resultDiv.textContent = 'Masukkan angka positif yang valid.';
                numberInput.classList.add('error');
                return;
            } else {
                numberInput.classList.remove('error');
            }

            try {
                const response = await fetch(`${URL}/api/akar-kuadrat/${number}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                const data = await response.json();
                if (data.error) {
                    resultDiv.textContent = data.error;
                } else {
                    const resultText = `Akar kuadrat dari ${number} adalah ${data.hasil}`;
                    resultDiv.textContent = resultText;

                    // Add to history
                    fetchHistory();
                
                    // Add the 'visible' class to show the result with animation
                    resultDiv.classList.add('visible');
                }
            } catch (error) {
                resultDiv.textContent = 'Terjadi kesalahan saat mengambil hasil.';
            }
        }

        // Clear result
        function clearResult() {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = '';
            numberInput.value = ''; // Clear input field
            numberInput.classList.remove('error');

            // Remove the 'visible' class to hide the result
            resultDiv.classList.remove('visible');
        }

        // Add to history table
        function addToHistory(result) {
            const historyRow = document.createElement('tr');
            const inputCell = document.createElement('td');
            const resultCell = document.createElement('td');
            const typeCell = document.createElement('td');
            const executionTimeCell = document.createElement('td');

            inputCell.textContent = result.angka;
            resultCell.textContent = result.hasil;
            typeCell.textContent = result.method;
            executionTimeCell.textContent = result.execution_time;
            historyRow.appendChild(inputCell);
            historyRow.appendChild(resultCell);
            historyRow.appendChild(typeCell);
            historyRow.appendChild(executionTimeCell);

            historyList.appendChild(historyRow);
        }

        // fetch table history from database
        async function fetchHistory(){
            await fetch(`${URL}/api/akar-kuadrat`).then(res => res.json()).then(data => {
                
                // object to array
                const result = Object.values(data);
                // clear table
                historyList.innerHTML = '';
                // add to table
                result.forEach(element => {
                    addToHistory(element);
                });
            });
            
        }

        // Format result based on selected decimal places
        function result(){
            const selectedDecimalPlaces = parseInt(decimalPlacesSelect.value);
            return selectedDecimalPlaces === 0 ? Math.round(result) : result.toFixed(selectedDecimalPlaces);
        }

        // Clear history table
        function clearHistory() {
            const historyList = document.getElementById('history-list');
            historyList.innerHTML = ''; // Clear all the history entries
        }

        // Keyboard Shortcuts
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                calculateSquareRoot();
            } else if (event.key === 'Escape') {
                clearResult();
            }
        });
    </script>
</body>
</html>