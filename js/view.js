$(document).ready(function() {
    getUser();
});

function selectDB() {
    var db = document.getElementById("selectOption").value;
    $(".dbstatus").text(' fetching..');
    if (db == 'default')
        return;
    var url = "view/getData.php";
    var params = "db=" + db;
    var http = new XMLHttpRequest();
    http.open("GET", url + "?" + params, true);
    http.send(null);
    var a = "<table id='table' class='display'>";
    http.onreadystatechange = function() { //Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            a += http.responseText;
            a += "</table>";
            document.getElementById("result").innerHTML = a;
            $(".dbstatus").text('');
            enhance();
        }
    };
}
var user;

function getUser() {
    var http = new XMLHttpRequest();
    var url = "view/getUserInfo.php";
    http.open("GET", url, true);
    http.send(null);
    http.onreadystatechange = function() { //Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var data = JSON.parse(http.responseText);
            user = data.name;
            user = user + '!';
            if (data.admin == 'no') {
                $("#adminBtn").prop('disabled', true);
                $("#adminBtn").css('background-color', '#E5E5E5');
            }
            $("<p id='greeting'>Hi " + user + "</p>").appendTo('span.user');
            getListOfDB();
        }
    };
}
var tables;

function getListOfDB() {
    var list = document.getElementById("selectOption");
    var option = document.createElement("option");
    var http = new XMLHttpRequest();
    var url = "view/getDB.php";
    http.open("GET", url, true);
    http.send(null);
    http.onreadystatechange = function() { //Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var jo = JSON.parse(http.responseText);
            tables = jo;
            for (var i = 0; i < jo.view.length; i++) {
                var a = jo.view[i];
                var option = document.createElement("option");
                option.text = a.name;
                list.add(option);
            }

        }
    };

}
var flag;
var rowData;
var headers = [];

function enhance() {
    $(".editpane").html("");
    var table = $('#table').DataTable({
        paging: false,
        scrollY: 400,
        select: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            text: 'Save as excel'
        }]
    });

    table.column(0).visible(false);
    $('<button id="edit">Edit</button>').appendTo('div.dataTables_filter');
    $('<button id="delete">Delete</button>').appendTo('div.dataTables_filter');
    $('<button id="add">Add</button>').appendTo('div.dataTables_filter');
    var tedit = [];
    for (var i = 0; i < tables.edit.length; i++) { //done for disabling edit if no rights
        tedit.push(tables.edit[i].name);
    }
    if (tedit.indexOf($("#selectOption").val()) == -1){
        $("#edit").prop('disabled', true);
        $("#delete").prop('disabled', true);
        $("#add").prop('disabled', true);
    }

    table.on('select', function(e, dt, type, indexes) {
        rowData = table.row(indexes).data();
    });

    $("#edit").click(function() {
        if (rowData === undefined) {
            alert("Please select the row to edit.");
            return;
        }
        $(".editpane").html("");
        spawnInputFields();
        $("#addTuple").hide();
        $("#update").show();

        for (var i = 0; i < rowData.length; i++) {
            var title = table.columns(i).header();
            var t = $(title).html();
            headers.push(t);
        }

        for (i = 0; i < rowData.length; i++) {
            if (rowData[i] === "") {
                $("<input type='text' value='' placeholder='" + headers[i] + "' id='e" + headers[i] + "'>").appendTo('div.fields');
                continue;
            }
            $("<input type='text' placeholder='" + headers[i] + "' value='" + rowData[i] + "' id='e" + headers[i] + "'>").appendTo('div.fields');
        }

        $("#eUID").hide();
        $("<span id='status'></span>").appendTo('div.editpane');
    });

    $("#delete").click(function() {
        if (rowData === undefined) {
            alert("Please select the row to delete.");
            return;
        }
        $(".editpane").html("");

        var db = $("#selectOption").val();
        var jdata = {UID: rowData[0], table: db};
        jdata = JSON.stringify(jdata);
        $.ajax({
            url: 'view/deleteTuple.php',
            type: 'POST',
            contentType: 'application/json',
            data: jdata,

            success: function(data) {
                alert(data);
                selectDB();
            },

            error: function(xhr, ajaxOptions, thrownError) {
                if (xhr.status == 200) {

                    alert(ajaxOptions);
                } else {
                    alert(xhr.status);
                    alert(thrownError);
                }
            }
        });
    });

    $("#add").click(function() {
        rowData = table.row(0).data(); //for the sake of getting the header val
        $(".editpane").html("");
        spawnInputFields();
        $("#update").hide();
        $("#addTuple").show();
        for (var i = 0; i < rowData.length; i++) {
            var title = table.columns(i).header();
            var t = $(title).html();
            headers.push(t);
        }

        for (i = 0; i < rowData.length; i++)
            $("<input type='text' value='' placeholder='" + headers[i] + "' id='a" + headers[i] + "'>").appendTo('.fields');

        $("#aUID").hide();
        $("<span id='status'></span>").appendTo('div.editpane');
    });

    function spawnInputFields() {
        $("<div class='fields'></div>").appendTo("div.editpane");
        $("<button id='update' onclick='update()'>Update</button>").appendTo("div.editpane");
        $("<button id='addTuple' onclick='addUser()'>Add</button>").appendTo("div.editpane");
    }
}

function adminLogin() {
    window.location = "control.php";
}

function addUser() {
    var db = $("#selectOption").val();
    var jo = {};
    jo.table = db;
    for (var i = 0; i < rowData.length; i++) {
        //this selects the textbox
        text = $("[id='a" + headers[i] + "']");
        jo[headers[i]] = text.val();
    }
    var jdata = JSON.stringify(jo);
    $.ajax({
        url: 'view/addTuple.php',
        type: 'POST',
        contentType: 'application/json',
        data: jdata,

        success: function(data) {
            $("#status").text(data);
            $("#status").show('slow');
            selectDB();
        },

        error: function(xhr, ajaxOptions, thrownError) {
            if (xhr.status == 200) {

                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });
}

function update() {
    var db = $("#selectOption").val();
    var jo = {};
    jo.table = db;
    for (var i = 0; i < rowData.length; i++) {
        //this selects the textbox
        text = $("[id='e" + headers[i] + "']");
        jo[headers[i]] = text.val();
    }
    var jdata = JSON.stringify(jo);
    $.ajax({
        url: 'view/updateDB.php',
        type: 'POST',
        contentType: 'application/json',
        data: jdata,

        success: function(data) {
            $("#status").text(data);
            $("#status").show('slow');
            selectDB();
        },

        error: function(xhr, ajaxOptions, thrownError) {
            if (xhr.status == 200) {

                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });
}
