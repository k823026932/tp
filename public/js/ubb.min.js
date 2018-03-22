function ubb2html(a) {
    {
        var b;
        "10px,13px,16px,18px,24px,32px,48px".split(",")
    }
    for (b = ("" + a).replace(/[<>&"]/g,
        function(a) {
            return {
                "<": "&lt;",
                ">": "&gt;",
                "&": "&amp;",
                '"': "&quot;"
            } [a]
        }), b = b.replace(/\r?\n/g, "<br />"), b = b.replace(/\[(\/?)(b|u|i|s|sup|sub)\]/gi, "<$1$2>"), b = b.replace(/\[color\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/gi, '<font color="$1">'), b = b.replace(/\[font\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/gi, '<font face="$1">'), b = b.replace(/\[\/(color|font)\]/gi, "</font>"), b = b.replace(/\[size\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/gi,
        function(a, b) {
            return n = b.match(/\d+/),
                n ? (n = n[0], n = n > 48 ? 48 : n) : n = 13,
                '<span style="font-size:' + n + 'px;">'
        }), b = b.replace(/\[back\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/gi, '<span style="background-color:$1;">'), b = b.replace(/\[\/(size|back)\]/gi, "</span>"), a = 0; 3 > a; a++) b = b.replace(/\[align\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\](((?!\[align(?:\s+[^\]]+)?\])[\s\S])*?)\[\/align\]/gi, '<p align="$1">$2</p>');
    //return b = b.replace(/\[url\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*\[\/url\]/gi,'<a href="$1">$1</a>'),
    return b = b.replace(/\[url\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*\[\/url\]/gi,function(a,b,c){
            var tDom = document.createElement('a');
            tDom.href = b;
            if(tDom.protocol === 'http:'){
                return '<a href="'+b+'" target="_blank">'+b+'</a>'
            }else if(tDom.protocol === 'https:'){
                return '<a href="'+b+'" target="_blank">'+b+'</a>';
            }else{
                return '<a href="">'+b+'</a>';
            }
        }),
        //b = b.replace(/\[url\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]*?)\s*\[\/url\]/gi, '<a href="$1">$2</a>'),
        b = b.replace(/\[url\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]*?)\s*\[\/url\]/gi, function(a,b,c){
            var tDom = document.createElement('a');
            b = b.replace(/^<br \/>/ig,'');

            tDom.href = b;
            if(tDom.protocol === 'http:'){
                return '<a href="'+b+'" target="_blank">'+c+'</a>'
            }else if(tDom.protocol === 'https:'){
                return '<a href="'+b+'" target="_blank">'+c+'</a>';
            }else if(tDom.protocol === 'javascript:' && /^javascript:window\.appealPopup/ig.test(b)){
                return '<a href="'+b+'">'+c+'</a>';
            }else if(tDom.protocol === 'javascript:' && /^javascript:window\.albumAssistants/ig.test(b)) {
                return '<a href="'+b+'">'+c+'</a>';
            }else{
                return '<a href="">'+c+'</a>';
            }
        }),
        b = b.replace(/\[acdiv\](((?!<\1(\s+[^>]*?)?>)[\s\S])+?)\[\/acdiv\]/gi, '<div class="comm-video">$1</div>'),
        b = b.replace(/\[email\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/email\]/gi, '<a href="mailto:$1">$1</a>'),
        b = b.replace(/\[email\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]+?)\s*\[\/email\]/gi, '<a href="mailto:$1">$2</a>'),
        b = b.replace(/\[hr\/\]/gi, "<hr />"),
        b = b.replace(/(^|<\/?\w+(?:\s+[^>]*?)?>)([^<$]+)/gi,
            function(a, b, c) {
                return b + c.replace(/[\t ]/g,
                    function(a) {
                        return {
                            "	": "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
                            " ": "&nbsp;"
                        } [a]
                    })
            })
}
function html2ubb(a) {
    function b(a, b, d, e) {
        if (!d) return e;
        var f, a = [],
            b = [];
        return (f = d.match(/ face\s*=\s*"\s*([^"]+)\s*"/i)) && (a.push("[font=" + f[1] + "]"), b.push("[/font]")),
            (f = d.match(/ size\s*=\s*"\s*(\d+)\s*"/i)) && (a.push("[size=" + f[1] + "]"), b.push("[/size]")),
            (f = d.match(/ color\s*=\s*"\s*([^"]+)\s*"/i)) && (a.push("[color=" + c(f[1]) + "]"), b.push("[/color]")),
            a.join("") + e + b.join("")
    }
    function c(a) {
        var b;
        if (b = a.match(/\s*rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i)) {
            for (a = (65536 * b[1] + 256 * b[2] + 1 * b[3]).toString(16); 6 > a.length;) a = "0" + a;
            a = "#" + a
        }
        return a = a.replace(/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i, "#$1$1$2$2$3$3")
    }
    var d, e = /\s+src\s*=\s*(["']?)\s*(.+?)\s*\1(\s|$)/i;
    for (d = ("" + a).replace(/\s*\r?\n\s*/g, ""), d = d.replace(/<(script|style)(\s+[^>]*?)?>[\s\S]*?<\/\1>/gi, ""), d = d.replace(/<\!--[\s\S]*?--\>/gi, ""), d = d.replace(/<br(\s+[^>]*)?\/?>/gi, "\r\n"), d = d.replace(/<(\/?)(b|u|i|s)(\s+[^>]*?)?>/gi, "[$1$2]"), d = d.replace(/<(\/?)strong(\s+[^>]*?)?>/gi, "[$1b]"), d = d.replace(/<(\/?)em(\s+[^>]*?)?>/gi, "[$1i]"), d = d.replace(/<(\/?)(strike|del)(\s+[^>]*?)?>/gi, "[$1s]"), d = d.replace(/<(\/?)(sup|sub)(\s+[^>]*?)?>/gi, "[$1$2]"), d = d.replace(/<(font)(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/gi, b), d = d.replace(/<(font)(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?)<\/\1>/gi, b), d = d.replace(/<(font)(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S])*?)<\/\1>/gi, b), a = 0; 3 > a; a++) d = d.replace(/<(span)(?:\s+[^>]*?)?\s+style\s*=\s*"((?:[^"]*?;)*\s*(?:font-family|font-size|color|background|background-color)\s*:[^"]*)"(?: [^>]+)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/gi,
        function(a, b, d, e) {
            var a = d.match(/(?:^|;)\s*font-family\s*:\s*([^;]+)/i),
                b = d.match(/(?:^|;)\s*font-size\s*:\s*([^;]+)/i),
                f = d.match(/(?:^|;)\s*color\s*:\s*([^;]+)/i),
                d = d.match(/(?:^|;)\s*(?:background|background-color)\s*:\s*([^;]+)/i),
                g = [],
                h = [];
            return a && (g.push("[font=" + a[1] + "]"), h.push("[/font]")),
                b && (g.push("[size=" + b[1] + "]"), h.push("[/size]")),
                f && (g.push("[color=" + c(f[1]) + "]"), h.push("[/color]")),
                d && (g.push("[back=" + c(d[1]) + "]"), h.push("[/back]")),
                g.join("") + e + h.join("")
        });
    // for (a = 0; 3 > a; a++) d = d.replace(/<(acdiv)>(((?!<\1(\s+[^>]*?)?>)[\s\S])+?)<\/\1>/gi, "[acdiv]$2[/acdiv]");
  // for (a = 0; 3 > a; a++){
  //   // acdiv = d.match(/<(acdiv)>(((?!<\1(\s+[^>]*?)?>)[\s\S])+?)<\/\1>/gi);
  //   // if(acdiv){
    d = d.replace(/(<a(?:\s+[^>]*?)?\s+class\s*=\s*"acfun-video-a"\s+href=(["'])\s*(.+?)\s*\1[^>]*>\s*([\s\S]*?)\s*<\/a>)/gi,'[acdiv]$1[/acdiv]')
  //   // }d =
    // d = d.replace(/<(acdiv)>(((?!<\1(\s+[^>]*?)?>)[\s\S])+?)<\/\1>/gi, "[acdiv]$2[/acdiv]");
  // }
    for (a = 0; 3 > a; a++) d = d.replace(/<(div|p)(?:\s+[^>]*?)?[\s"';]\s*(?:text-)?align\s*[=:]\s*(["']?)\s*(left|center|right)\s*\2[^>]*>(((?!<\1(\s+[^>]*?)?>)[\s\S])+?)<\/\1>/gi, "[align=$3]$4[/align]");
    for (a = 0; 3 > a; a++) d = d.replace(/<(center)(?:\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S])*?)<\/\1>/gi, "[align=center]$2[/align]");
    for (a = 0; 3 > a; a++) d = d.replace(/<(p|div)(?:\s+[^>]*?)?\s+style\s*=\s*"(?:[^;"]*;)*\s*text-align\s*:([^;"]*)[^"]*"(?: [^>]+)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/gi,
        function(a, b, c, d) {
            return "[align=" + c + "]" + d + "[/align]"
        });
    for (d = d.replace(/<a(?:\s+[^>]*?)?\s+href=(["'])\s*(.+?)\s*\1[^>]*>\s*([\s\S]*?)\s*<\/a>/gi,
        function(a, b, c, d) {
            return c && d ? (a = "url", c.match(/^mailto:/i) && (a = "email", c = c.replace(/mailto:(.+?)/i, "$1")), b = "[" + a, c != d && (b += "=" + c), b + "]" + d + "[/" + a + "]") : ""
        }),
       d = d.replace(/<img\s+class="acfun-video-img"(\s+[^>]*?)\/?>/gi,
         function(a, b) {
           var c = b.match(e),
             d = b.match(/\s+title\s*=\s*(["']?)\s*(.*?)\s*\1(\s|$)/i),
             f = "[acimg";
           return c ? (d = d ? "" + d[2] : "图片", d && (f += "=" + d), f += "]" + c[2] + "[/acimg]") : ""
         }),
        d = d.replace(/<img(\s+[^>]*?)\/?>/gi,
        function(a, b) {
            var c = b.match(e),
                d = b.match(/\s+title\s*=\s*(["']?)\s*(.*?)\s*\1(\s|$)/i),
                f = "[img";
            return c ? (d = d ? "" + d[2] : "图片", d && (f += "=" + d), f += "]" + c[2] + "[/img]") : ""
        }), d = d.replace(/<hr(\s+[^>]*?)?\/>/gi, "[hr/]"), a = 0; 3 > a; a++) d = d.replace(/<(p)(?:\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/gi, "$2\r\n");
    for (a = 0; 3 > a; a++) d = d.replace(/<(div)(?:\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/gi, "\r\n$2\r\n");
    d = d.replace(/((\s|&nbsp;)*\r?\n){3,}/g, "\r\n\r\n"),
        d = d.replace(/^((\s|&nbsp;)*\r?\n)+/g, ""),
        d = d.replace(/((\s|&nbsp;)*\r?\n)+$/g, ""),
        d = d.replace(/<[^<>]+?>/g, "");
    var f = {
        lt: "<",
        gt: ">",
        nbsp: " ",
        amp: "&",
        quot: '"'
    };
    return d = d.replace(/&(lt|gt|nbsp|amp|quot);/gi,
        function(a, b) {
            return f[b]
        }),
        d = d.replace(/\[([a-z]+)(?:=[^\[\]]+)?\]\s*\[\/\1\]/gi, "")
}
