
$(document).ready(function () {

    localStorage.getItem('token') == null ? location.href = 'http://127.0.0.1:8000/Max/login' : null;

})


function submit() {

    var url = "http://127.0.0.1:8000/Max/api/db?"
    var get = {}


    // get vale from date and team 
    get['date'] = document.getElementById('fdate').value;
    get['team'] = document.getElementById('fteam').value;
    get['token'] = localStorage.getItem('token');

    // set GET method parameter
    for (key in get) {

        url += '&' + key + "=" + get[key]
    }


    $.ajax({
        url: url,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
        success: function (response) {

            if (response.errors === undefined) {
                show(response)
            } else {
                $('#rows').html("")
            }

        },
        error: function (err) {
            $('#rows').html("")
        }
    })

}

function show(payload) {


    var html = `
        <tr>
            <th>Number</th>
            <th>Competition</th>
            <th>Time</th>
            <th>Away team</th>
            <th>Home team</th>
            <th>Count</th>
        </tr>
    `
    payload.forEach(row => {

        rowCountHtml = colorTag([row['lose'], row['win']])

        html += `
        <tr>
            <td>` + row['no'] + `</td>
            <td>` + row['type'] + `</td>
            <td>` + row['time'] + `</td>
            <td>` + row['away_team'] + `</td>
            <td>` + row['home_team'] + `</td>
            <td><div style="display:flex">` + rowCountHtml + `</div></td>
        </tr>
        `
    })

    $('#rows').html(html)
}

function colorTag(count) {

    html = ""

    count.forEach(res => {
        if (res > 2) {
            html += "<p style='color:red'>" + res + "&emsp;</p>"
        } else {
            html += "<p style='color:blue'>" + res + "&emsp;</p>"
        }
    })

    return html
}


function logout() {


    console.log("Clear now")


    $.ajax({
        url: 'http://127.0.0.1:8000/Max/api/user/logout?',
        method: 'POST',
        data: {
            token: localStorage.getItem('token')
        }
    }).done(res => {

        localStorage.removeItem('token');
        location.href = 'http://127.0.0.1:8000/Max/login';
    })




}