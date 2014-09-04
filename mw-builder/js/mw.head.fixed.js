/**
 * Head-Fixed
 *
 * Copyright (c) 2014 Choi Jae-Young <www.miwit.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

var first_head_fixed = true;

var head_fixed =
{
    init: function (w, h)
    {
/*
        if (w > 100) {
            $(".head-fixed").css("width", w+4);
            $(".head-fixed").css("margin-left", -2);
        }
        else {
            $(".head-fixed").css("width", w + '%');
        }

        $(".head-fixed").css("height", h);
        $(".head-fixed").css("position", "fixed");
        $(".head-fixed").css("background-color", "#fff");
        $(".head-fixed").css("z-index", 99);

        $(".head-fixed-back").css("font-size", 0);
        $(".head-fixed-back").css("line-height", 0);
        $(".head-fixed-back").css("clear", "both");
        $(".head-fixed-back").css("height", h);
*/

        var css = null;

        css  = "<style>";
        css += ".head-fixed { ";
        if (w > 100) {
            css += " width:" + (w+4) + "px; ";
            css += " margin-left: -2px; ";
        }
        else {
            css += " width:" + w + "%; ";
        }
        css += "  height:" + h + "px; ";
        css += "  position: fixed; ";
        css += "  background-color:#fff; ";
        css += "  z-index:99; ";
        css += " }";

        css += " .head-fixed-back { ";
        css += "  font-size: 0; ";
        css += "  line-height: 0; ";
        css += "  clear: both; ";
        css += "  height: " + h + "px; ";
        css += "  *height: " + h-10 + "px; ";
        css += " } ";

        $("body").append(css);
    },

    run: function (w, h)
    {
        if (!h) h = 80;

        if (first_head_fixed) head_fixed.init(w, h);

        sct = $(window).scrollTop();

        if (sct < h) {
            first_head_fixed = false;
            if ($("#head-fixed").hasClass("head-fixed")) {
                $("#head-fixed").css("top", h-sct);
                $("#head-fixed").removeClass("head-fixed");
                $("#head-fixed-back").css("display", "none");
            }
        }
        else {
            if (first_head_fixed) {
                $(window).scrollTop(sct-h);
                first_head_fixed = false;
            }

            if (!$("#head-fixed").hasClass("head-fixed")) {
                $("#head-fixed").css("top", 0);
                $("#head-fixed").addClass("head-fixed");
                $("#head-fixed-back").css("display", "block");
            }
        }
    }
}

