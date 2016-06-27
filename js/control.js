var flag;
var aflag;
var headers = [];
$(document).ready(function() {
    $("#addPane").hide();
    $("#modifyPane").hide();
    $("#dbPane").hide();
    $(".mTableList").hide();
    $(".editpane").hide();

    flag = false;
    aflag = false;

    $("#add").click(function() {
        $("#modifyPane").hide();
        $("#dbPane").hide();
        $("#addPane").show();
        if (aflag === false) {
            $.ajax({
                url: 'control/getTablesControl.php',
                type: 'GET',

                success: function(data) {
                    //On ajax success do this
                    populate(data);
                },

                error: function(xhr, ajaxOptions, thrownError) {
                    //On error do this
                    if (xhr.status == 200) {

                        alert(ajaxOptions);
                    } else {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                }
            });

            function populate(data) {
                var jo = JSON.parse(data);
                for (var i = jo.tables.length - 1; i >= 0; i--) {
                    $("<input type='checkbox' id='" + jo.tables[i] + "'>" + jo.tables[i] + "</input><br>").appendTo('div.viewList');
                    $("<input type='checkbox' id='" + jo.tables[i] + "'>" + jo.tables[i] + "</input><br>").appendTo('div.editList');
                };
            }
            aflag = true;
        }
    });

    $("#modify").click(function() {
        $("#addPane").hide();
        $("#dbPane").hide();
        $("#modifyPane").show();
        getUserDB();
    });

    $("#dbBtn").click(function() {
        $("#addPane").hide();
        $("#modifyPane").hide();
        $("#dbPane").show();
        getTables();
    });
});

function addUser() {
    var name = $("#name").val();
    var uname = $("#username").val();
    var pwd = $("#password").val();
    var admin = $("input[name='admin']:checked").val();
    var view = [];
    var edit = [];

    $('.editList input:checked').each(function() {
        var temp = {};
        temp["name"] = $(this).attr('id');
        edit.push(temp);
        view.push(temp);
    });

    $('.viewList input:checked').each(function() {
        var temp = {};
        temp["name"] = $(this).attr('id');
        var result = $.grep(view, function(e) {
            return e.name == temp["name"];
        });
        if (result.length == 0)
            view.push(temp);
    });

    var table = {
        "view": view,
        "edit": edit
    };

    var data = {
        "name": name,
        "username": uname,
        "password": pwd,
        "table": table,
        "admin": admin
    };
    data = JSON.stringify(data);

    $.ajax({
        url: 'control/addUser.php',
        type: 'POST',
        contentType: 'application/json',
        data: data,

        success: function(data) {
            //On ajax success do this
            alert(data);
        },

        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            if (xhr.status == 200) {

                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });
}

function getUserDB() {
    var url = "view/getData.php";
    var params = "db=users"
    var http = new XMLHttpRequest();
    http.open("GET", url + "?" + params, true);
    http.send(null);
    var a = "<table id='table' class='display'>";
    http.onreadystatechange = function() { //Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            a += http.responseText;
            a += "</table>";
            document.getElementById("usersTable").innerHTML = a;
            enhance();
        }
    };
}

function enhance() {
    var table = $('#table').DataTable({
        paging: false,
        scrollY: 400,
        select: true
    });

    table.column(0).visible(false);
    table.column(5).visible(false);
    $('<button id="edit">Edit</button>').appendTo('div.dataTables_filter');
    $('<button id="refresh">Refresh</button>').appendTo('div.dataTables_filter');

    var data;

    table.on('select', function(e, dt, type, indexes) {
        data = table.row(indexes).data();
    });

    $("#edit").click(function() {
        for (var i = 0; i < data.length; i++) {
            var title = table.columns(i).header();
            var t = $(title).html();
            headers.push(t);
        }
        if (flag === false) {
            for (var j = 0; j < data.length; j++) {
                $('#change').before("<input type='text' placeholder='" + headers[j] + "' value='" + data[j] + "' id='m" + headers[j] + "'>");
            }

            $(".mTableList").show();
            $(".editpane").show();
            $("#mUID").hide();
            $("#mtables").hide();

            flag = true;
        } else {
            for (var k = 0; k < data.length; k++) {
                $("#m" + headers[k]).val(data[k]);
            }

        }
        loadModifyPanel(data);
    });

}
$("#change").click(function() {
    modifyUser(headers);
});

$("#refresh").click(function() {
    getUserDB();
});

function modifyUser(headers) {
	var admin_val=$("#madmin").val();
	if(admin_val!='yes' && admin_val!='no'){
		alert("Please enter 'yes' or 'no' in the admin field");
		return;
	}
    var jdata = {};
    var edit = [];
    var view = [];
    for (var i = 0; i < headers.length; i++) {
        text = $("#m" + headers[i]); //this selects the textbox
        jdata[headers[i]] = text.val(); //headers is the id of the text box that was set above
    }

    $('.mEditList input:checked').each(function() {
        var temp = {};
        var text = $(this).attr('id');
        temp["name"] = text.slice(1);
        edit.push(temp);
        view.push(temp);
    });

    $('.mViewList input:checked').each(function() {
        var temp = {};
        var text = $(this).attr('id');
        temp["name"] = text.slice(1);
        var result = $.grep(view, function(e) {
            return e.name == temp["name"];
        });
        if (result.length == 0)
            view.push(temp);
    });

    var table = {
        "view": view,
        "edit": edit
    };

    jdata["tables"] = table;
    jdata = JSON.stringify(jdata);

    $.ajax({
        url: 'control/modifyUser.php',
        type: 'POST',
        contentType: 'application/json',
        data: jdata,

        success: function(data) {
            //On ajax success do this
            alert(data);
        },

        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            if (xhr.status == 200) {

                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });
}

function loadModifyPanel(data) {
    $(".mViewList").html("");
    $(".mEditList").html("");

    var table = data[5]; //Gets the json table object

    $.ajax({ //Gets list of all tables
        url: 'control/getTablesControl.php',
        type: 'GET',

        success: function(data) {
            //On ajax success do this
            //tdata=data;
            populate(data);
        },

        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            if (xhr.status == 200) {

                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });

    function populate(data) {
        var jo = JSON.parse(data);
        for (var i = jo.tables.length - 1; i >= 0; i--) {
            $("<input type='checkbox' id='v" + jo.tables[i] + "'>" + jo.tables[i] + "</input><br>").appendTo('div.mViewList');
            $("<input type='checkbox' id='e" + jo.tables[i] + "'>" + jo.tables[i] + "</input><br>").appendTo('div.mEditList');
        }

        table = JSON.parse(table);

        var view = table["view"];
        var edit = table["edit"];

        for (var j = view.length - 1; j >= 0; j--) {
            $("#v" + view[j]["name"]).prop('checked', true);
        }
        for (var k = edit.length - 1; k >= 0; k--) {
            $("#e" + edit[k]["name"]).prop('checked', true);
        }
    }

}

function getTables() {
    $(".dbTable").text("");
    $.ajax({
        url: 'control/getTables.php',
        type: 'GET',

        success: function(data) {
            //On ajax success do this
            displayTables(data);
        },

        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            if (xhr.status == 200) {
                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });

    function displayTables(json) {
        var table = "<table>";
        table += "<thead><tr><td>Database</td><td>Uploader</td></tr></thead>";
        var data = JSON.parse(json);
        var i = 0;
        while (i < data["data"].length) {
            table += "<tr id='" + data["data"][i]["UID"] + "'><td>" + data["data"][i]["tables"] + "</td><td>" + data["data"][i]["name"] + "</td>";
            table += "<td><button onclick='deleteDB(" + data["data"][i]["UID"] + ")'>Delete</button></td></tr>"
            i++;
        }
        table += "</table>";
        $(table).appendTo("div.dbTable");
    }
}

function deleteDB(uid) {
    var c = confirm("Are you sure? All data will be lost.");
    if (c == false)
        return;
    $(".dbstatus").text("deleting");
    uid = JSON.stringify(uid);
    $.ajax({
        url: 'control/deleteDB.php',
        type: 'POST',
        contentType: 'application/json',
        data: uid,

        success: function(data) {
            alert(data);
            $(".dbstatus").text("");
            getTables();
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
