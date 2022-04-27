<html>

<head>
    <script src="https://cdn.staticfile.org/jquery/2.0.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- <script src="../js/main.js"></script> -->
    <!-- <link href="../css/styled.css" rel="stylesheet"> -->
</head>


<body>

    <div>
        <label>Date: </label>
        <input type="text" id="fdate" placeholder="xxxx-xx-xx">
        <button onclick="submit()">submit</button>
    </div>

    <div>
        <label>Search: </label>
        <input type="text" id="fteam">
        <button onclick="submit()">搜尋</button>

    </div>
    <div class="contest-spinner" style="visibility: collapse;">
        <div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">
            <span class="sr-only"></span>
        </div>
    </div>


    <div id="detial">

        <table class="table table-striped table-bordered" id="rows">

        </table>


    </div>




</body>

<script>
    function submit() {

        $('#rows').html("")
        $('.contest-spinner').css(
            'visibility', 'visible'
        )
        var url = "http://127.0.0.1:8000/Max/api?"
        var get = {}
        get['date'] = document.getElementById('fdate').value;
        get['team'] = document.getElementById('fteam').value;

        for (key in get) {

            // &key=value
            url += '&' + key + "=" + get[key]
        }


        $.ajax({
            url: url,
            success: function(response) {
                if (response.success) {
                    show(response.payload)
                } else {
                    $('#rows').html("<p>" + response.error_message + "</p>")
                }
                $('.contest-spinner').css(
                    'visibility', 'collapse'
                )
            }
        })

    }

    function show(payload) {

        var html = `
<thead>
    <tr>
        <th scope="col">#</th>
        <th>Competition</th>
        <th>Time</th>
        <th>Away team</th>
        <th>Home team</th>
        <th>Count</th>
    </tr>
</thead>
`


        payload.forEach(row => {

            rowCountHtml = colorTag([row['lose'], row['win']])

            html += `
    <tr>
        <th scope ="row">` + row['no'] + `</th>
        <td>` + row['type'] + `</td>
        <td>` + row['time'] + `</td>
        <td>` + row['away_team'] + `</td>
        <td>` + row['home_team'] + `</td>
        <td><div style="display:flex">` + rowCountHtml + `</div></td>
    </tr>
    `
        })
        console.log(html)

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
</script>

</html>