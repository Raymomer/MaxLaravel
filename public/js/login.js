function login() {
    console.log("login now")

    reqParameter = {
        account: $('#inputAccount').val(),
        password: $('#inputPassword').val()
    };

    $.ajax({
        url: "http://127.0.0.1:8000/Max/api/user/login?",
        method: "POST",
        data: reqParameter
    }).done(res => {

        if (res.status) {
            localStorage.setItem('token', res.payload.token)
            location.href = "http://127.0.0.1:8000/Max"
        } else {
            alert("Login failed! Please check Account/Password is current.")
        }


    }).error(err => {

        localStorage.removeItem('token');
        alert("Login Error!");
    })




}


function regist() {

    reqParameter = {
        account: $('#inputAccount').val(),
        password: $('#inputPassword').val(),
        mail: $('#inputMail').val()
    };

    $.ajax({
        url: "http://127.0.0.1:8000/Max/api/user/create?",
        method: "POST",
        data: reqParameter
    }).done(res => {
        if (res.status) {
            alert("Create user success, please login !");
        } else {
            localStorage.removeItem('token');
            alert("Create failed!");
        }
    }).error(err => {

        localStorage.removeItem('token');
        alert("Login Error!");
    })




}