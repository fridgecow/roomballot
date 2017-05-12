<?
require_once "../php/common.php";
header("Content-Type: application/javascript");
?>$(document).ready(function() {
    $("#loading").remove();
    $(window).on("hashchange", function(e) {
        var a = $("#pages a[href='" + location.hash + "']")[0];
        if (!a) return location.hash = "#home";
        if ($(a).parent().hasClass("active")) return;
        $("#pages li.active").removeClass("active");
        $(".page").hide();
        var hash = a.href.substr(a.href.indexOf("#"));
        // lazy load images
        $(hash + " img.delay").each(function(i, img) {
            $(img).prop("src", $(this).data("src")).removeClass("delay");
        });
        $(hash).show();
        $(a).parent().addClass("active");
        window.scroll(0, 0);
    }).trigger("hashchange");
    var click = false;
    $("#pages a[href^='#']").click(function(e) {
        var nav = $("#pages").parent();
        if (nav.hasClass("in")) nav.collapse("hide");
<?
if (auth(true)) {
?>
        // update allocations dynamically
        updateAllocs(this.href.substr(this.href.indexOf("#")) === "#allocated");
<?
}
?>
    }).each(function(i, a) {
        if (this.href.substr(this.href.indexOf("#")) === location.hash) {
            this.click();
            click = true;
            return false; // break
        }
    });
    if (!click) $("#pages a[href^='#']")[0].click();
    $($("#overview-prompt a")[0]).click(function(e) {
        e.preventDefault();
        $("#overview table.overview-responsive").removeClass("overview-responsive");
        $("#overview-prompt").remove();
    });
    $("#overview tr.room, #available tr.room").click(function(e) {
        var tds = $("td", this);
        $("#popup h4.modal-title").text($(tds[0]).text());
        var desc = $("<em/>").addClass("text-muted").text("No description has been added for this room.");
        if ($(this).data("desc")) desc = $("<div/>").addClass("well well-sm").html(markdown.toHTML($(this).data("desc")));
        $("#popup-desc").empty().append(desc);
        $("#popup-type").text($(tds[1]).text());
        var floors = {
            "B": "Basement",
            "G": "Ground",
            "1": "1st",
            "2": "2nd",
            "3": "3rd",
            "4": "4th"
        };
        $("#popup-floor").text(floors[$(tds[2]).text()]);
        $("#popup-bathroom").text($(tds[3]).text());
        $("#popup-storage").text($(tds[5]).text());
        $("#popup-wifi").text($(tds[6]).text());
        $("#popup-sockets").text($(tds[7]).text());
        $("#popup-view").text($(tds[8]).text());
        var facings = {
            "N": "North",
            "NE": "North-East",
            "E": "East",
            "SE": "South-East",
            "S": "South",
            "SW": "South-West",
            "W": "West",
            "NW": "North-West",
            "—": "—"
        };
        $("#popup-facing").text(facings[$(tds[9]).text()]);
        $("#popup-balcony").text($(tds[10]).text());
<?
if (auth(true)) {
?>
        var tr = $(this);
        var fields = ["desc", "bathroom", "sink", "storage", "wifi", "sockets", "view", "facing", "balcony"];
        function initEdit() {
            $("#popup-edit-desc").val(tr.data("desc"));
            for (var i in fields) {
                var ii = parseInt(i);
                if (ii === 0) continue;
                $("#popup-edit-" + fields[i]).val($(tds[ii + 2]).text());
            }
        }
        initEdit();
        var room = $(this).data("room");
        $("#popup-edit-bathroom").change(function(e) {
            if (this.value === "Ensuite") $("#popup-edit-sink").val("Yes");
        });
        $("#popup form").submit(function(e) {
            e.preventDefault();
            $("#popup .modal-body .alert").remove();
            var btn = $("input[type=submit]", this);
            btn.prop("disabled", true).val("Saving...");
            var data = {
                "room": room
            };
            for (var i in fields) {
                data[fields[i]] = $("#popup-edit-" + fields[i]).val();
                if (data[fields[i]] === "—") data[fields[i]] = null;
            }
            $.ajax({
                "url": "/<?=$_GET["yr"]?>/api/editRoom",
                "method": "post",
                "data": data,
                "success": function(resp, stat, xhr) {
                    tr.data("desc", data["desc"]);
                    for (var i in fields) {
                        var ii = parseInt(i);
                        if (ii == 0) continue;
                        $(tds[ii + 2]).text(data[fields[i]] ? data[fields[i]] : "—");
                    }
                    btn.prop("disabled", false).val("Save");
                    $("#popup").modal("hide");
                },
                "error": function(xhr, stat, err) {
                    $("#allocate .modal-body").prepend($("<div/>").addClass("alert alert-danger").text(xhr.responseText));
                    btn.prop("disabled", false).val("Save");
                }
            });
        }).on("reset", function(e) {
            e.preventDefault();
            initEdit();
            $("#popup").trigger("hidden.bs.modal");
        });
        $("#popup").on("hidden.bs.modal", function(e) {
            $("#popup form").off("submit reset");
            $("#popup ul.nav-tabs li a")[0].click();
        });
<?
}
?>
        $("#popup").modal("show");
    });
<?
if (auth(true)) {
?>
    $("#popup-md").click(function(e) {
        e.preventDefault();
        alert("### heading\n_italic_\n**bold**\n[link](URL)\n![image](URL)\n\n* list item 1\n* list item 2\n\n1. numbered item 1\n2. numbered item 2");
    });
    var allocInterval;
    function updateAllocs(on) {
        if (!on) {
            clearInterval(allocInterval);
            allocInterval = false;
            return;
        }
        if (allocInterval) return;
        var allocUpdater = function() {
            $.ajax({
                "url": "/<?=$_GET["yr"]?>/api/allocations",
                "dataType": "json",
                "success": function(resp, stat, xhr) {
                    for (var i in resp) {
                        var alloc = $("#allocated span[data-room=" + i + "]");
                        if (resp[i]) alloc.removeClass("free").addClass("taken").data("occupant", resp[i]);
                        else alloc.removeClass("taken").addClass("free").removeData("occupant").removeAttr("data-occupant");
                    }
                }
            });
        };
        allocUpdater();
        allocInterval = setInterval(allocUpdater, 5000);
    }
<?
}
if (auth(true, true)) {
?>
    $("#allocate").on("shown.bs.modal", function(e) {
        $("#allocate-name").focus();
    });
    $("#allocs span").click(function(e) {
        if ($(this).hasClass("unav")) return;
        $("#allocate h4.modal-title").text($(this).text());
        $("#allocate-room").val($(this).data("room"));
        $("#allocate-name").val($(this).data("occupant"));
        $("#allocate").data("span", this).modal("show");
    }).mouseover(function(e) {
        if ($(this).data("occupant")) $("#alloc-hover").text($(this).text() + ": " + $(this).data("occupant"));
    }).mouseout(function(e) {
        $("#alloc-hover").text("");
    });
    $("#allocate-form").submit(function(e) {
        e.preventDefault();
        $("#allocate .modal-body .alert").remove();
        var btn = $("input[type=submit]", this);
        btn.prop("disabled", true).val("Saving...");
        $.ajax({
            "url": "/<?=$_GET["yr"]?>/api/allocate",
            "method": "post",
            "data": {
                "room": $("#allocate-room").val(),
                "name": $("#allocate-name").val()
            },
            "success": function(resp, stat, xhr) {
                btn.prop("disabled", false).val("Save");
                if ($("#allocate-name").val()) $($("#allocate").data("span")).removeClass("free").addClass("taken").data("occupant", $("#allocate-name").val());
                else $($("#allocate").data("span")).removeClass("taken").addClass("free").removeData("occupant").removeAttr("data-occupant");
                $("#allocate").modal("hide");
            },
            "error": function(xhr, stat, err) {
                $("#allocate .modal-body").prepend($("<div/>").addClass("alert alert-danger").text(xhr.responseText));
                btn.prop("disabled", false).val("Save");
            }
        });
    });
<?
} elseif (auth(true)) {
?>
    $("#allocs span").on("click mouseover", function(e) {
        if ($(this).data("occupant")) $("#alloc-hover").text($(this).text() + ": " + $(this).data("occupant"));
    }).mouseout(function(e) {
        $("#alloc-hover").text("");
    });
<?
}
?>
});
