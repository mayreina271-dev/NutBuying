// =========================================
// AUTO COMPUTE TOTALS
// =========================================

function calculateTotal(){

    let gross = parseFloat(
        document.getElementById('gross').value
    ) || 0;

    let tare = parseFloat(
        document.getElementById('tare').value
    ) || 0;

    let price = parseFloat(
        document.getElementById('price').value
    ) || 0;

    let net = gross - tare;

    let total = net * price;

    document.getElementById('net').value =
        net.toFixed(2);

    document.getElementById('total').value =
        total.toFixed(2);
}

// =========================================
// EVENT LISTENERS
// =========================================

document
.getElementById('gross')
.addEventListener(
    'input',
    calculateTotal
);

document
.getElementById('tare')
.addEventListener(
    'input',
    calculateTotal
);

document
.getElementById('price')
.addEventListener(
    'input',
    calculateTotal
);

// =========================================
// CONFIRM VOID
// =========================================

function confirmVoid(){

    return confirm(
        "Are you sure you want to void this delivery?"
    );

}

// =========================================
// SEARCH TABLE
// =========================================

function searchTable(){

    let input =
        document.getElementById('searchInput');

    let filter =
        input.value.toLowerCase();

    let table =
        document.getElementById('deliveryTable');

    let tr =
        table.getElementsByTagName('tr');

    for(let i = 1; i < tr.length; i++){

        let td =
            tr[i].getElementsByTagName('td');

        let found = false;

        for(let j = 0; j < td.length; j++){

            if(
                td[j]
                .innerHTML
                .toLowerCase()
                .indexOf(filter) > -1
            ){

                found = true;

            }

        }

        tr[i].style.display =
            found ? '' : 'none';

    }

}

// =========================================
// REALTIME CLOCK
// =========================================

function updateClock(){

    let now = new Date();

    let time =
        now.toLocaleTimeString();

    let clock =
        document.getElementById('clock');

    if(clock){

        clock.innerHTML = time;

    }

}

setInterval(updateClock,1000);

// =========================================
// AUTO REFRESH TABLE
// =========================================

setInterval(function(){

    console.log(
        'Auto refresh purchase table'
    );

},15000);