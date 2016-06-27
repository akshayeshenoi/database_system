$(document).ready(function() {
    getUsers();
});

function initUpload() {
    var xlf = document.getElementById('xlf');
    $("#status").text('uploading');
    handleFile(xlf.files);
}

var sendRawFile = function() {
    var formData = new FormData($('form')[0]);
    $.ajax({
        url: 'upload/uploadFile.php',
        type: 'POST',
        data: formData,

        success: function(data) {
            //On ajax success do this
            alert(data);
            $("#status").text('');
        },

        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            if (xhr.status == 200) {
                alert(ajaxOptions);
            } else {
                alert(xhr.status);
                alert(thrownError);
            }
        },
        contentType:false,
        processData: false,
        cache: false
    });
};


function handleFile(fileList) {
    var files = fileList;
    var i, f;
    for (i = 0, f = files[i]; i != files.length; ++i) {
        var reader = new FileReader();
        var name = f.name;
        if(name.slice(-5)!='.xlsx'){
            alert("Please upload file of .xlsx extension only");
            return;   
        }
        name = name.toLowerCase();
        name = name.replace(" ", "_");
        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, { type: 'binary' });
            var output = "";
            output = to_json(workbook);
            var sheet = Object.keys(output)[0];
            output.data = output[sheet];
            delete output[sheet];
            output.tableName = name.replace(/\.[^/.]+$/, "");   //to remove the extension
            output.tableName = output.tableName.replace(/[\(\)' '\*\%\#\!\@\^\&\+\{\}\'\"\;\:\>\?\/\~\`\.]/g,"_");
            output.users = addUserRights();
            output.headers = get_headers(workbook);
            var json = JSON.stringify(output);
            console.log(json);
            sendFile(json);
        };
        reader.readAsBinaryString(f);
    }
}

function sendFile(json) {
    $.ajax({
        url: 'upload/uploadDB.php',
        type: 'POST',
        contentType: 'application/json',
        data: json,

        success: function(data) {
            //On ajax success do this
            alert(data);
            $("#status").text(data + '.. Uploading raw file..');
            sendRawFile();
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

function to_json(workbook) {
    var result = {};
    workbook.SheetNames.forEach(function(sheetName) {
        var roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
        if (roa.length > 0) {
            result[sheetName] = roa;
        }
    });
    return result;
}

function get_headers(workbook) {
    var first_sheet_name = workbook.SheetNames[0];
    var worksheet = workbook.Sheets[first_sheet_name];
    var headers = [];
    // var range =worksheet['!ref'];

    var i = 0;
    var alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    while (true) {
        try {
            var address = alpha[i] + "1";
            var cell = worksheet[address];
            console.log(cell.v);
            headers.push(cell.v);
            i++;
        } catch (err) {
            break;
        }
    }
    return headers;
}

function getUsers() {
    var url = "upload/getUsers.php";
    var http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send(null);
    http.onreadystatechange = function() { //Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var json = http.responseText;
            var users = JSON.parse(json);
            for (var i = users.data.length - 1; i >= 0; i--) {
                console.log(users.data[i].UID);
                $("<input type='checkbox' id='" + users.data[i].UID + "'> " + users.data[i].name + "</input><br>").appendTo('div.viewList');
                $("<input type='checkbox' id='" + users.data[i].UID + "'> " + users.data[i].name + "</input><br>").appendTo('div.editList');
            }
        }
    };
}

function addUserRights() {
    var users = [];
    $('.editList input:checked').each(function() {
        var temp = {};
        temp.UID = $(this).attr('id');
        temp.edit = "yes";
        temp.view = "yes";
        users.push(temp);
    });

    $('.viewList input:checked').each(function() {
        var temp = {};
        temp.UID = $(this).attr('id');
        var result = $.grep(users, function(e) {
            return e.UID == temp.UID;
        });
        if (result.length === 0) {
            temp.edit = "no";
            temp.view = "yes";
            users.push(temp);
        }
    });
    return users;
}
