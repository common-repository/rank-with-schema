/*!
 * Development Lib
 * Copyright 2019
 *
 */

var activeTab, imageButtonClicked, mediaUploader, currentClickedImageField, firstTab = !1;

function uploadImage() {
    var e = event.currentTarget.id.split("_");
    currentClickedImageField = e[0] + "_" + e[1], mediaUploader ? mediaUploader.open() : ((mediaUploader = wp.media.frames.file_frame = wp.media({
        title: "Choose Image",
        button: {
            text: "Choose Image"
        },
        multiple: !1
    })).on("select", function() {
        var e = mediaUploader.state().get("selection").first().toJSON();
        jQuery("#" + currentClickedImageField).val(e.url)
    }), mediaUploader.open())
}

function addFields() {
    var e = jQuery("#rwsschema_panel_box #" + event.currentTarget.id).attr("field_index");
    e++;
    var a = event.currentTarget.id.split("_")[0],
        t = jQuery("." + a).first().clone();
    t.find("fieldset").each(function() {
        var a = this.parentElement.parentElement;
        a.id = a.id + "_" + e
    }), t.find("a").each(function() {
        var a = jQuery(this).attr("data-target") + "_" + e;
        jQuery(this).attr("data-target", a);
        var t = this.innerHTML;
        t = (t = t.split("-")[0]) + "-" + (e + 1), this.innerHTML = t
    }), t.find("input").each(function() {
        var a = this.name,
            t = this.id.replace("0", e),
            i = a.replace("0", e);
        this.name = i, this.id = t, "hidden" != this.type && (this.value = "")
    }), t.find("select").each(function() {
        var a = this.name,
            t = this.id,
            i = a.replace("0", e),
            r = t.replace("0", e);
        this.name = i, this.id = r, this.value = ""
    }), t.find("textarea").each(function() {
        var a = this.name,
            t = this.id,
            i = a.replace("0", e),
            r = t.replace("0", e);
        this.name = i, this.id = r
    }), jQuery("#rwsschema_panel_box #" + event.currentTarget.id).before(t), jQuery("#rwsschema_panel_box #" + activeTab).validator("update"), jQuery("#rwsschema_panel_box #" + event.currentTarget.id).attr("field_index", e)
}
jQuery(document).ready(function(e) {
    activeTab = e("#rwsschema_panel_box .tabs .active").text(), e('#rwsschema_panel_box a[data-toggle="tab"]').on("shown.bs.tab", function(a) {
        activeTab = jQuery(a.target).text().replace(/\s/g, ""), e("#" + activeTab).validator().on("submit", function(a) {
            if (1 == firstTab) return firstTab = !1, !1;
            if (a.isDefaultPrevented()) return !1;
            var t = jQuery("#rwsschema_panel_box #" + activeTab).serialize();
            return e("#rwsschema_panel_box #" + activeTab + "-loader").removeClass("hidden"), jQuery.post(RWS_MBAjax.ajaxurl, t, function(a) {
                var t = e.parseJSON(a);
                1 == t.success ? (e("#rwsschema_panel_box #" + activeTab + "-msg").removeClass("hidden"), e("#rwsschema_panel_box #" + activeTab + "-msg").removeClass("alert-danger"), e("#rwsschema_panel_box #" + activeTab + "-msg").addClass("alert-success"), e("#rwsschema_panel_box #" + activeTab + "-msg").html("<p>" + t.msg + "</p>"), setTimeout(function() {
                    e("#rwsschema_panel_box #" + activeTab + "-msg").addClass("hidden")
                }, 5e3)) : (e("#rwsschema_panel_box #" + activeTab + "-msg").removeClass("hidden"), e("#rwsschema_panel_box #" + activeTab + "-msg").addClass("alert-danger"), e("#rwsschema_panel_box #" + activeTab + "-msg").html("<p>" + t.msg + "</p>"), setTimeout(function() {
                    e("#rwsschema_panel_box #" + activeTab + "-msg").addClass("hidden")
                }, 5e3)), e("#rwsschema_panel_box #" + activeTab + "-loader").addClass("hidden")
            }), !1
        })
    }), e("#rwsschema_panel_box #" + activeTab).validator().on("submit", function(a) {
        if (firstTab = !0, !a.isDefaultPrevented()) {
            var t = jQuery("#rwsschema_panel_box #" + activeTab).serialize();
            return e("#rwsschema_panel_box #" + activeTab + "-loader").removeClass("hidden"), jQuery.post(RWS_MBAjax.ajaxurl, t, function(a) {
                var t = e.parseJSON(a);
                1 == t.success ? (e("#rwsschema_panel_box #" + activeTab + "-msg").removeClass("hidden"), e("#rwsschema_panel_box #" + activeTab + "-msg").removeClass("alert-danger"), e("#rwsschema_panel_box #" + activeTab + "-msg").addClass("alert-success"), e("#rwsschema_panel_box #" + activeTab + "-msg").html("<p>" + t.msg + "</p>")) : (e("#rwsschema_panel_box #" + activeTab + "-msg").removeClass("hidden"), e("#rwsschema_panel_box #" + activeTab + "-msg").addClass("alert-danger"), e("#rwsschema_panel_box #" + activeTab + "-msg").html("<p>" + t.msg + "</p>")), e("#rwsschema_panel_box #" + activeTab + "-loader").addClass("hidden")
            }), !1
        }
    })
});