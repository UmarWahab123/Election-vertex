<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>#i2soft-keyboard{width:630px;line-height:20px;font-size:1em}.i2soft-key,#i2soft-backspace,#i2soft-tab,#i2soft-k25,#i2soft-caps-lock,#i2soft-enter,#i2soft-left-shift,#i2soft-right-shift,#i2soft-space,#i2soft-left-ctrl,#i2soft-right-ctrl,#i2soft-left-alt,#i2soft-right-alt,#i2soft,#i2soft-escape{float:left;display:block;margin:1px;height:3em;line-height:2.75em;text-align:center;color:gray}.i2soft-key{width:40px}#i2soft-backspace{width:78px}#i2soft-tab{width:62px}#i2soft-k25{width:56px}#i2soft-caps-lock{width:76px}#i2soft-enter{width:84px}#i2soft-left-shift{width:46px}#i2soft-right-shift{width:114px}#i2soft-space{width:246px;text-align:center}#i2soft-right-ctrl,#i2soft-right-alt,#i2soft-escape{width:62px}#i2soft-left-ctrl,#i2soft-left-alt,#i2soft{width:60px}.i2soft-label-reference{color:gray;font-size:.9em;line-height:12px;text-align:left;cursor:default}.i2soft-label-natural{margin-top:-5px;color:#e0115f;line-height:20px;text-align:center;cursor:default}.i2soft-label-shift{margin-top:-5px;color:#057cb5;line-height:20px;text-align:center;cursor:default}#i2soft-k29 .i2soft-label-reference,#i2soft-k32 .i2soft-label-reference{color:#000}.i2soft-recessed span{color:#3C0}.i2soft-recessed-hover span{color:#ffd800}.i2soft-clear{clear:both}</style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
    <script>var i2soft={};i2soft.util={keyCode:function(b){if(!b){var b=window.event}if($.browser.mozilla){var a=b.keyCode;switch(a){case 59:a=186;break;case 107:a=187;break;case 109:a=189;break;case 61:a=187;break;case 173:a=189;break}return a}if($.browser.opera){var a=b.keyCode;switch(a){case 59:a=186;break;case 61:a=187;break;case 109:a=189;break}return a}return b.keyCode},isCtrl:function(a){if(!a){var a=window.event}return a.ctrlKey},isAlt:function(a){if(!a){var a=window.event}return a.altKey},isShift:function(a){if(!a){var a=window.event}return a.shiftKey},insertAtCaret:function(a,f){var d=this.getSelectionStart(a);var b=this.getSelectionEnd(a);var c=a.value.length;a.value=a.value.substring(0,d)+f+a.value.substring(b,c);this.setCaretPosition(a,d+f.length,0)},deleteAtCaret:function(c,b,a){var g=this.getSelectionStart(c);var d=this.getSelectionEnd(c);var f=c.value.length;if(b>g){b=g}if(d+a>f){a=f-d}var h=c.value.substring(g-b,d+a);c.value=c.value.substring(0,g-b)+c.value.substring(d+a);this.setCaretPosition(c,g-b,0);return h},getSelectionStart:function(a){a.focus();if(a.selectionStart!==undefined){return a.selectionStart}else{if(document.selection){var b=document.selection.createRange();if(b==null){return 0}var d=a.createTextRange();var c=d.duplicate();d.moveToBookmark(b.getBookmark());c.setEndPoint("EndToStart",d);return c.text.length}}return 0},getSelectionEnd:function(a){a.focus();if(a.selectionEnd!==undefined){return a.selectionEnd}else{if(document.selection){var b=document.selection.createRange();if(b==null){return 0}var d=a.createTextRange();var c=d.duplicate();d.moveToBookmark(b.getBookmark());c.setEndPoint("EndToStart",d);return c.text.length+b.text.length}}return a.value.length},setCaretPosition:function(b,d,a){var c=b.value.length;if(d>c){d=c}if(d+a>c){a=c-a}b.focus();if(b.setSelectionRange){b.setSelectionRange(d,d+a)}else{if(b.createTextRange){var f=b.createTextRange();f.collapse(true);f.moveEnd("character",d+a);f.moveStart("character",d);f.select()}}b.focus()},selectAll:function(a){this.setCaretPosition(a,0,a.value.length)}};i2soft.layout=function(){this.keys=[];this.deadkeys=[];this.dir="ltr";this.name="US";this.lang="en"};i2soft.layout.prototype.loadDefault=function(){this.keys=[{i:"k0",c:"0",n:"`",s:"~"},{i:"k1",c:"0",n:"1",s:"!"},{i:"k2",c:"0",n:"2",s:"@"},{i:"k3",c:"0",n:"3",s:"#"},{i:"k4",c:"0",n:"4",s:"$"},{i:"k5",c:"0",n:"5",s:"%"},{i:"k6",c:"0",n:"6",s:"^"},{i:"k7",c:"0",n:"7",s:"&"},{i:"k8",c:"0",n:"8",s:"*"},{i:"k9",c:"0",n:"9",s:"("},{i:"k10",c:"0",n:"0",s:")"},{i:"k11",c:"0",n:"-",s:"_"},{i:"k12",c:"0",n:"=",s:"+"},{i:"k13",c:"1",n:"q",s:"Q"},{i:"k14",c:"1",n:"w",s:"W"},{i:"k15",c:"1",n:"e",s:"E"},{i:"k16",c:"1",n:"r",s:"R"},{i:"k17",c:"1",n:"t",s:"T"},{i:"k18",c:"1",n:"y",s:"Y"},{i:"k19",c:"1",n:"u",s:"U"},{i:"k20",c:"1",n:"i",s:"I"},{i:"k21",c:"1",n:"o",s:"O"},{i:"k22",c:"1",n:"p",s:"P"},{i:"k23",c:"0",n:"[",s:"{"},{i:"k24",c:"0",n:"]",s:"}"},{i:"k25",c:"0",n:"\\",s:"|"},{i:"k26",c:"1",n:"a",s:"A"},{i:"k27",c:"1",n:"s",s:"S"},{i:"k28",c:"1",n:"d",s:"D"},{i:"k29",c:"1",n:"f",s:"F"},{i:"k30",c:"1",n:"g",s:"G"},{i:"k31",c:"1",n:"h",s:"H"},{i:"k32",c:"1",n:"j",s:"J"},{i:"k33",c:"1",n:"k",s:"K"},{i:"k34",c:"1",n:"l",s:"L"},{i:"k35",c:"0",n:";",s:":"},{i:"k36",c:"0",n:"'",s:'"'},{i:"k37",c:"1",n:"z",s:"Z"},{i:"k38",c:"1",n:"x",s:"X"},{i:"k39",c:"1",n:"c",s:"C"},{i:"k40",c:"1",n:"v",s:"V"},{i:"k41",c:"1",n:"b",s:"B"},{i:"k42",c:"1",n:"n",s:"N"},{i:"k43",c:"1",n:"m",s:"M"},{i:"k44",c:"0",n:",",s:"<"},{i:"k45",c:"0",n:".",s:">"},{i:"k46",c:"0",n:"/",s:"?"},{i:"k47",c:"0",n:"\\",s:"|"}];this.dir="ltr";this.name="US";this.lang="en"};i2soft.layout.prototype.load=function(a){this.keys=a.keys;this.deadkeys=a.deadkeys;this.dir=a.dir;this.name=a.name;this.lang=a.lang?a.lang:"en"};i2soft.layout.parser={keyCodes:[192,49,50,51,52,53,54,55,56,57,48,189,187,81,87,69,82,84,89,85,73,79,80,219,221,220,65,83,68,70,71,72,74,75,76,186,222,90,88,67,86,66,78,77,188,190,191,220],getKeyCode:function(c,e,b){var d=c.length;for(var a=0;a<d;a++){if(c[a].i==b){return e==1?(c[a].s?c[a].s:""):(c[a].n?c[a].n:"")}}return 0},getKey:function(c,b){var d=c.length;for(var a=0;a<d;a++){if(c[a].i==b){return c[a]}}return null},isDeadkey:function(a,d){if(!a){return false}var c=a.length;for(var b=0;b<c;b++){if(a[b].k==d){return true}}return false},getMappedValue:function(a,e,d){if(!a){return""}var c=a.length;for(var b=0;b<c;b++){if(a[b].k==d&&a[b].b==e){return a[b].c}}return""},getKeyId:function(b){for(var a=0;a<48;a++){if(this.keyCodes[a]==b){return a}}return -1},getState:function(d,a,e,b,c){var f="n";if(!a&&!e&&d){f="n"}else{if(!a&&e&&!d){f="s"}else{if(!a&&e&&d){f="s"}else{if(a&&!e&&!d){f="n"}else{if(a&&!e&&d){f="t"}else{if(a&&e&&!d){f="s"}else{if(a&&e&&d){f="f"}}}}}}}if((f=="n"||f=="s")&&b){if(c=="1"){if(f=="n"){f="s"}else{f="n"}}if(c=="SGCap"){if(f=="n"){f="y"}else{if(f=="s"){f="z"}}}}return f}};i2soft.keyboard=function(a,d){this.defaultLayout=new i2soft.layout();this.defaultLayout.loadDefault();this.virtualLayout=new i2soft.layout();this.virtualLayout.loadDefault();this.currentLayout=this.virtualLayout;this.shift=false;this.shiftOn=false;this.caps=false;this.capsOn=false;this.alt=false;this.ctrl=false;this.altCtrlOn=false;this.fontSize=18;this.counter=0;this.interval=0;this.prev="";this.cancelkeypress=false;this.customOnBackspace=function(e){};this.customOnEnter=function(){};this.customOnSpace=function(){return false};this.customOnKey=function(e){return false};this.customOnEsc=function(){};this.customDrawKeyboard=function(e){return e};this.textbox=$("#"+d);this.nativeTextbox=document.getElementById(d);var c=['<div id="i2soft-keyboard">'];for(var b=0;b<13;b++){c.push('<button id="i2soft-k',b,'" class="i2soft-key"></button>')}c.push('<button id="i2soft-backspace"><span>Backspace</span></button>');c.push('<div class="i2soft-clear"></div>');c.push('<button id="i2soft-tab"><span>Tab</span></button>');for(var b=13;b<25;b++){c.push('<button id="i2soft-k',b,'" class="i2soft-key"></button>')}c.push('<button id="i2soft-k25"></button>');c.push('<div class="i2soft-clear"></div>');c.push('<button id="i2soft-caps-lock"><span>Caps Lock</span></button>');for(var b=26;b<37;b++){c.push('<button id="i2soft-k',b,'" class="i2soft-key"></button>')}c.push('<button id="i2soft-enter" class="i2soft-enter"><span>Enter</span></button>');c.push('<div class="i2soft-clear"></div>');c.push('<button id="i2soft-left-shift"><span>Shift</span></button>');c.push('<button id="i2soft-k47" class="i2soft-key"></button>');for(var b=37;b<47;b++){c.push('<button id="i2soft-k',b,'" class="i2soft-key"></button>')}c.push('<button id="i2soft-right-shift"><span>Shift</span></button>');c.push('<div class="i2soft-clear"></div>');c.push('<button id="i2soft-left-ctrl"><span>Ctrl</span></button>');c.push('<button id="i2soft"><span>i2soft</span></button>');c.push('<button id="i2soft-left-alt"><span>Alt</span></button>');c.push('<button id="i2soft-space"><span>Space</span></button>');c.push('<button id="i2soft-right-alt"><span>Alt</span></button>');c.push('<button id="i2soft-escape" title="Turn on/off keyboard input conversion"><span>Esc</span></button>');c.push('<button id="i2soft-right-ctrl"><span>Ctrl</span></button>');c.push('<div class="i2soft-clear"></div>');c.push("</div>");document.getElementById(a).innerHTML=c.join("");this.wireEvents();this.drawKeyboard()};i2soft.keyboard.prototype.loadDefaultLayout=function(a){this.defaultLayout.load(a);this.drawKeyboard()};i2soft.keyboard.prototype.loadVirtualLayout=function(a){this.virtualLayout.load(a);this.drawKeyboard();this.textbox.attr("dir",this.attr("dir"))};i2soft.keyboard.prototype.switchLayout=function(){this.currentLayout=(this.currentLayout===this.defaultLayout)?this.virtualLayout:this.defaultLayout;this.reset();this.drawKeyboard();this.textbox.attr("dir",this.attr("dir"))};i2soft.keyboard.prototype.onEsc=function(){this.switchLayout();this.customOnEsc()};i2soft.keyboard.prototype.onShift=function(){this.shift=!this.shift;this.drawKeyboard()};i2soft.keyboard.prototype.onAlt=function(){this.alt=!this.alt;this.drawKeyboard()};i2soft.keyboard.prototype.onCtrl=function(){this.ctrl=!this.ctrl;this.drawKeyboard()};i2soft.keyboard.prototype.onCapsLock=function(){this.caps=!this.caps;this.drawKeyboard()};i2soft.keyboard.prototype.onBackspace=function(){if(this.prev!=""){this.prev="";this.shift=false;this.drawKeyboard()}else{var a=i2soft.util.deleteAtCaret(this.nativeTextbox,1,0);this.customOnBackspace(a)}};i2soft.keyboard.prototype.onEnter=function(){i2soft.util.insertAtCaret(this.nativeTextbox,"\u000A");this.customOnEnter()};i2soft.keyboard.prototype.onSpace=function(){if(!this.customOnSpace()){i2soft.util.insertAtCaret(this.nativeTextbox,"\u0020")}};i2soft.keyboard.prototype.attr=function(a){if(a=="dir"){return this.currentLayout.dir}else{if(a=="lang"){return this.currentLayout.lang}else{if(a=="name"){return this.currentLayout.name}}}return""};i2soft.keyboard.prototype.reset=function(){this.shift=false;this.caps=false;this.alt=false;this.ctrl=false;this.counter=0;this.interval=0;this.prev=""};i2soft.keyboard.prototype.stopRepeat=function(){if(this.interval!=0){clearInterval(this.interval);this.counter=0;this.interval=0}};i2soft.keyboard.prototype.onKey=function(b){var a=i2soft.layout.parser.getKey(this.currentLayout.keys,b);if(a){var d=i2soft.layout.parser.getState(this.ctrl,this.alt,this.shift,this.caps,a.c?a.c:"0");var e=a[d]?a[d]:"";if(this.prev!=""){var c=i2soft.layout.parser.getMappedValue(this.currentLayout.deadkeys,e,this.prev);if(c!=""){i2soft.util.insertAtCaret(this.nativeTextbox,c)}this.prev=""}else{if(i2soft.layout.parser.isDeadkey(this.currentLayout.deadkeys,e)){this.prev=e}else{if(e!=""){if(!this.customOnKey(e)){i2soft.util.insertAtCaret(this.nativeTextbox,e)}}}}}};i2soft.keyboard.prototype.drawKeyboard=function(){if(!this.currentLayout.keys){return}var d,f,j,k;var g=this.currentLayout.keys.length;for(var e=0;e<g;e++){f=this.currentLayout.keys[e];if(!$("i2soft-"+f.i)){continue}var c=this.ctrl;var a=this.alt;var h=this.shift;var b=this.caps;if(this.shiftOn){h=true}if(this.capsOn){b=true}if(this.altCtrlOn){c=true;a=true}j=i2soft.layout.parser.getState(c,a,h,b,f.c?f.c:"0");k=f[j]?f[j]:"";if(this.prev!=""){k=i2soft.layout.parser.getMappedValue(this.currentLayout.deadkeys,k,this.prev)}if(!h){k=this.customDrawKeyboard(k);if(k==""){k=" "}d='<div class="i2soft-label-reference">'+i2soft.layout.parser.getKeyCode(this.defaultLayout.keys,0,f.i)+'</div><div class="i2soft-label-natural" style="font-size:'+this.fontSize+'px;"> '+k+"</div>"}else{if(k==""){k=" "}d='<div class="i2soft-label-reference">'+i2soft.layout.parser.getKeyCode(this.defaultLayout.keys,0,f.i)+'</div><div class="i2soft-label-shift" style="font-size:'+this.fontSize+'px;"> '+k+"</div>"}document.getElementById("i2soft-"+f.i).innerHTML=d}$("#i2soft-left-ctrl").removeClass();$("#i2soft-right-ctrl").removeClass();if(c){$("#i2soft-left-ctrl").addClass("i2soft-recessed"+(this.ctrl?"":"-hover"));$("#i2soft-right-ctrl").addClass("i2soft-recessed"+(this.ctrl?"":"-hover"))}$("#i2soft-left-alt").removeClass();$("#i2soft-right-alt").removeClass();if(a){$("#i2soft-left-alt").addClass("i2soft-recessed"+(this.alt?"":"-hover"));$("#i2soft-right-alt").addClass("i2soft-recessed"+(this.alt?"":"-hover"))}$("#i2soft-left-shift").removeClass();$("#i2soft-right-shift").removeClass();if(h){$("#i2soft-left-shift").addClass("i2soft-recessed"+(this.shift?"":"-hover"));$("#i2soft-right-shift").addClass("i2soft-recessed"+(this.shift?"":"-hover"))}$("#i2soft-caps-lock").removeClass();if(b){$("#i2soft-caps-lock").addClass("i2soft-recessed"+(this.caps?"":"-hover"))}};i2soft.keyboard.prototype.wireEvents=function(){var a=this;$("#i2soft-keyboard").delegate("button","mousedown",function(b){var c=this.id;a.interval=setInterval(function(){a.counter++;if(a.counter>5){switch(c){case"i2soft-backspace":a.onBackspace();break;default:if(c.search("i2soft-k([0-9])|([1-3][0-9])|(4[0-7])")!=-1){a.onKey(c.substr(7));a.shift=false;a.alt=false;a.ctrl=false;a.drawKeyboard()}break}}},50)});$("#i2soft-keyboard").delegate("button","mouseup",function(b){a.stopRepeat()});$("#i2soft-keyboard").delegate("button","mouseout",function(b){a.stopRepeat()});$("#i2soft-keyboard").delegate("button","click",function(b){var c=this.id;switch(c){case"i2soft-left-shift":case"i2soft-right-shift":a.onShift();break;case"i2soft-left-alt":case"i2soft-right-alt":a.onCtrl();a.onAlt();break;case"i2soft-left-ctrl":case"i2soft-right-ctrl":a.onAlt();a.onCtrl();break;case"i2soft-escape":a.onEsc();break;case"i2soft-caps-lock":a.onCapsLock();break;case"i2soft-backspace":a.onBackspace();break;case"i2soft-enter":a.onEnter();break;case"i2soft-space":a.onSpace();break;default:if(c.search("i2soft-k([0-9])|([1-3][0-9])|(4[0-7])")!=-1){a.onKey(c.substr(7));a.shift=false;a.alt=false;a.ctrl=false;a.drawKeyboard()}break}});$("#i2soft-left-shift, #i2soft-right-shift").bind("mouseover",function(b){a.shiftOn=true;a.drawKeyboard()});$("#i2soft-left-shift, #i2soft-right-shift").bind("mouseout",function(b){a.shiftOn=false;a.drawKeyboard()});$("#i2soft-left-ctrl, #i2soft-right-ctrl").bind("mouseover",function(b){a.altCtrlOn=true;a.drawKeyboard()});$("#i2soft-left-ctrl, #i2soft-right-ctrl").bind("mouseout",function(b){a.altCtrlOn=false;a.drawKeyboard()});$("#i2soft-left-alt, #i2soft-right-alt").bind("mouseover",function(b){a.altCtrlOn=true;a.drawKeyboard()});$("#i2soft-left-alt, #i2soft-right-alt").bind("mouseout",function(b){a.altCtrlOn=false;a.drawKeyboard()});$("#i2soft-caps-lock").bind("mouseover",function(b){a.capsOn=true;a.drawKeyboard()});$("#i2soft-caps-lock").bind("mouseout",function(b){a.capsOn=false;a.drawKeyboard()});a.textbox.bind("keydown",function(b){var d=i2soft.util.keyCode(b);if((d==65||d==67||d==86||d==88||d==89||d==90)&&(a.ctrl&&!a.alt&&!a.shift)){return}if(a.currentLayout==a.defaultLayout&&d!=27){return}switch(d){case 17:a.ctrl=false;a.onCtrl();break;case 18:a.alt=false;a.onAlt();break;case 16:a.shift=false;a.onShift();break;case 27:a.onEsc();break;case 8:a.onBackspace();b.preventDefault();break;case 32:a.onSpace();b.preventDefault();break;case 10:a.onEnter();b.preventDefault();break;default:var c=i2soft.layout.parser.getKeyId(i2soft.util.keyCode(b));if(c!=-1){a.onKey("k"+c);a.drawKeyboard();b.preventDefault();a.cancelkeypress=true}break}});if($.browser.opera){a.textbox.bind("keypress",function(b){if(a.cancelkeypress){b.preventDefault();a.cancelkeypress=false}})}a.textbox.bind("keyup",function(b){switch(i2soft.util.keyCode(b)){case 17:a.ctrl=true;a.onCtrl();break;case 18:a.alt=true;a.onAlt();break;case 16:a.shift=true;a.onShift();break;default:}})};</script>
    <script>
        $.fn.setUrduInput=function(t){var n={q:"ق",w:"و",e:"ع",r:"ر",t:"ت",y:"ے",u:"ء",i:"ی",o:"ہ",p:"پ",a:"ا",s:"س",d:"د",f:"ف",g:"گ",h:"ح",j:"ج",k:"ک",l:"ل",z:"ز",x:"ش",c:"چ",v:"ط",b:"ب",n:"ن",m:"م","`":"ً",",":"،",".":"۔",Q:"ْ",W:"ّ",E:"ٰ",R:"ڑ",T:"ٹ",Y:"َ",U:"ئ",I:"ِ",O:"ۃ",P:"ُ",A:"آ",S:"ص",D:"ڈ",G:"غ",H:"ھ",J:"ض",K:"خ",Z:"ذ",X:"ژ",C:"ث",V:"ظ",N:"ں",M:"٘","~":"ٍ","?":"؟",F:"ٔ",L:"ل",B:"ب"},i={0:"۰",1:"۱",2:"۲",3:"۳",4:"۴",5:"۵",6:"۶",7:"۷",8:"۸",9:"۹"};t&&t.urduNumerals&&$.extend(n,i);var s="";$(this).bind("input",function(){var t=$(this)[0].selectionEnd,i=$(this).val(),e=t==i.length;if(s!=i){for(var a=[],h=0;h<i.length;h++){var r=i.charAt(h);a.push(n[r]||r)}$(this).val(a.join("")),s=$(this).val(),e||($(this)[0].selectionStart=$(this)[0].selectionEnd=t)}})};
    </script>
    <style>
        body {
            font-family: 'Noto Nastaliq Urdu Draft', serif;
        }
        th, td {
            border: 1px solid black;
            padding: 9px !important;
        }
        .table>thead>tr>th {
            vertical-align: bottom;
            border-bottom: 2px solid black !important;
            text-align: end;
        }
        td.cnic_number {
            /* padding: 10px 49px !important; */
            width: 15%;
        }
        .address-field{
            font-size: 0.95vw;
        }
        .name-filed{
            font-size: 11pt;
        }
        #i2soft-keyboard button span {
            font-size: 12px;
        }
        #editor {
            width: 100%;
            box-sizing: border-box;
            font-size: 32px !important;
            padding: 0 20px;
        }
        #keyboardModal .modal {
            width: 60vw;
            height: 48vh;
            overflow: hidden;
        }
        .editable-text {
            padding: 10px;
            height: 50px;
        }
        .modal-section .modal {
            top: 50% !important;
            transform: translate(-50%,0%) !important;
        }
        input.small-height {
            height: 40px;
            max-width: 10vw;
            padding: 5px;
        }
        .container {
            width: 100% !important;
        }
        .editable-field.orange {
            background-color: #fdce78;
        }
        .editable-field.green {
            background-color: lightgreen;
        }
        .editable-field.urdu input {
            text-align: right;
            width: 100%;
            box-sizing: border-box;
            font-size: 140% !important;
        }
        .keyboard-shortcuts {
            overflow-y: auto;
            max-height: 250px;
            overflow-x: hidden;
        }
        .keyboard-shortcuts > span {
            display: inline-block;
            padding: 10px 20px;
            background: #ddd;
            border-radius: 10px;
            margin: 5px;
        }
        .flex {
            display: flex;
        }
        tr.odd {
            background-color: #ededed;
        }
    </style>
    <style>.modal-section{z-index:10;position:fixed;background-color:rgba(0,0,0,.2);width:100vw;height:100vh;top:0;left:0;left:50%;transform:translate(-50%,0)}.modal-section.hide{display:none}.modal-section .modal{width:270px;border-radius:14px;background-color:#f8f8f8;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);display:block;height:unset}.modal-section .modal .top-part{padding:19px 10px 40px;text-align:center}.modal-section .modal .top-part .title{font-size:17px;line-height:22px;color:#000;padding:0;width:100%;display:block;text-align:center}.modal-section .modal .top-part .text{font-size:13px;letter-spacing:0;line-height:18px}.modal-section .modal .buttons{width:100%;display:flex}.modal-section .modal .buttons .modal-button{cursor:pointer;width:50%;padding:12px;display:flex;align-items:center;justify-content:center;font-size:17px;line-height:22px;color:#007aff}.modal-section .modal .buttons .modal-button:first-child{border-right:1px solid rgba(0,0,0,.2)}.modal-button.single{width:100%!important;border:none!important;}.modal .info{line-height:1.5em;}</style>
</head>
<body>

<section class="modal-section hide" id="keyboardModal">
    <div class="modal">
        <div class="top-part">
            <div class="title"></div>
            <div class="info">
                <p><input type="text" id="editor" name="editor" rows="" dir="rtl" /></p>
                <p><span id="response"></span></p>
                <div class="flex">
                    <div id="keyboard"></div>
                    <div class="keyboard-shortcuts">
                        <span>محمد </span>
                        <span>اللہ </span>
                        <span>احمد </span>
                        <span>علی </span>
                        <span>دختر </span>
                        <span>زوجہ </span>
                        <span>خان </span>
                        <span>ملک </span>
                        <span>حافظ </span>
                        <span>شيخ </span>
                        <span>بھٹی </span>
                        <span>محمود </span>
                        <span>اقبال </span>
                        <span>شهزاد </span>
                        <span>عثمان </span>
                        <span>سید </span>
                        <span>خواجہ </span>
                        <span>حسین </span>
                        <span>چوہدری </span>
                        <span>قریشی </span>
                        <span>عبدالرحمان </span>
                        <span>عائشہ </span>
                        <span>فیز </span>
                        <span>مکان نمبر </span>
                        <span>گلی </span>
                        <span>محلہ </span>
                        <span>لاہور کینٹ، </span>
                        <span>ضلع لاہور </span>
                        <span>ڈیفنس لاہور کینٹ، ضلع لاہور </span>
                        <span>ڈیفنس ہاوسنگ اتھارٹی، لاہور کینٹ، ضلع لاہور </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons">
            <div class="modal-button modal-save-changes">Save Changes</div>
            <div class="modal-button modal-close-btn">Close</div>
        </div>
    </div>
</section>

<div class="container">
    <hr>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading" style="text-align: center;">
                <h3 class="panel-title">Block Code # {{@$block_code}}</h3>
            </div>
            <table class="table text-right">

                <thead style="text-align-last: end;">
                <tr>
                    <th>پتہ</th>
                    <th>عمر</th>
                    <th>شناختی کارڈ</th>
                    <th>باپ / شوہر۔</th>
                    <th>نام </th>
                    <th>فون</th>
                    <th>فیملی نمبر</th>
                    <th>#</th>
                </tr>
                </thead>

                <tbody>
                @foreach($polling_details as $key => $line)
                    <tr class="editable-row @if($key % 2 !== 0) odd @else even @endif" id="{{$line->id}}" data-row_id="editable-row-id-{{$line->id}}">
                        <td class="address-field editable-field urdu @if(!$line->address) orange @endif" data-field_id="address" id="address-{{$line->id}}">
                            <input type="text" class="editable-text" value="{{@$line->address}}" placeholder="پتہ" dir="rtl">
                        </td>
                        <td>{{@$line->age}}</td>
                        <td class="cnic_number">{{@$line->cnic}}</td>
                        <td class="name-filed editable-field urdu @if(!$line->last_name) orange @endif" data-field_id="last_name" id="last_name-{{$line->id}}">
                            <input type="text" class="editable-text" value="{{@$line->last_name}}" placeholder="باپ / شوہر" dir="rtl">
                        </td>
                        <td class="name-filed editable-field urdu @if(!$line->first_name) orange @endif" data-field_id="first_name" id="first_name-{{$line->id}}">
                            <input type="text" class="editable-text" value="{{@$line->first_name}}" placeholder="نام" dir="rtl">
                        </td>
                        <td>{{ '0'.@$line->voter_phone->phone }}</td>
                        <td class="editable-field @if(!$line->family_no) orange @endif" data-field_id="family_no" id="family_no-{{$line->id}}">
                            <input type="text" class="editable-text small-height" value="{{@$line->family_no}}" placeholder="فیملی نمبر">
                        </td>
                        <td class="editable-field @if(!$line->serial_no) orange @endif" data-field_id="serial_no" id="serial_no-{{$line->id}}">
                            <input type="text" class="editable-text small-height" value="{{@$line->serial_no}}" placeholder="سیریل نمبر">
                        </td>
                    </tr>
                    <tr class="@if($key % 2 !== 0) odd @else even @endif">
                        <td colspan="8">
                            <img src="{{@$line->pic_slice}}" alt="Row Image" width="100%" loading="lazy">
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>

<input type="hidden" id="current_edited_row_id">
<input type="hidden" id="current_edited_field_name">

<script>
    (function($){
        const KeyBoardModel = {
            hide: () => {
                const m = document.querySelector('.modal-section')
                // m.querySelector('#editor').value = ""
                document.querySelector('#current_edited_row_id').value = ""
                document.querySelector('#current_edited_field_name').value = ""
                m.classList.add('hide')
            },
            show: (rowId, fieldName, editorValue) => {
                const m = document.querySelector('.modal-section')
                document.querySelector('#current_edited_row_id').value = rowId
                document.querySelector('#current_edited_field_name').value = fieldName
                document.querySelector('#editor').value = editorValue
                m.classList.remove('hide')
            },
            save: (rowId, fieldId, newText) => {
                const row = document.querySelector(`.editable-row[id='${rowId}']`)
                console.log(row)
                if(row) {
                    row.querySelector(`.editable-field[data-field_id='${fieldId}'] .editable-text`).value = newText
                }
            },
            init: () => {
                document.querySelector('.modal-close-btn').addEventListener('click', e => {
                    KeyBoardModel.hide();
                })
            }
        }
        $(document).ready(() => {
            $('#editor').setUrduInput();
            // let keyboard = new i2soft.keyboard("keyboard", "editor");
            // keyboard.fontSize = 20;
            // keyboard.loadVirtualLayout({"name":"Urdu","dir":"rtl","keys":[{"i":"k0","c":"0","n":"`","s":"~","l":"","t":"","f":""},{"i":"k1","c":"0","n":"1","s":"!","l":"","t":"","f":""},{"i":"k2","c":"0","n":"2","s":"@","l":"","t":"","f":""},{"i":"k3","c":"0","n":"3","s":"#","l":"","t":"","f":""},{"i":"k4","c":"0","n":"4","s":"$","l":"","t":"","f":""},{"i":"k5","c":"0","n":"5","s":"٪","l":"","t":"","f":""},{"i":"k6","c":"0","n":"6","s":"^","l":"","t":"","f":""},{"i":"k7","c":"0","n":"7","s":"ۖ","l":"","t":"","f":""},{"i":"k8","c":"0","n":"8","s":"٭","l":"","t":"","f":""},{"i":"k9","c":"0","n":"9","s":")","l":"","t":"","f":""},{"i":"k10","c":"0","n":"0","s":"(","l":"","t":"","f":""},{"i":"k11","c":"0","n":"-","s":"_","l":"","t":"","f":""},{"i":"k12","c":"0","n":"=","s":"+","l":"","t":"","f":""},{"i":"k13","c":"0","n":"ط","s":"ظ","l":"","t":"","f":""},{"i":"k14","c":"0","n":"ص","s":"ض","l":"","t":"","f":""},{"i":"k15","c":"0","n":"ھ","s":"ذ","l":"","t":"","f":""},{"i":"k16","c":"0","n":"د","s":"ڈ","l":"","t":"","f":""},{"i":"k17","c":"0","n":"ٹ","s":"ث","l":"","t":"","f":""},{"i":"k18","c":"0","n":"پ","s":"ّ","l":"","t":"","f":""},{"i":"k19","c":"0","n":"ت","s":"ۃ","l":"","t":"","f":""},{"i":"k20","c":"0","n":"ب","s":"ـ","l":"","t":"","f":""},{"i":"k21","c":"0","n":"ج","s":"چ","l":"","t":"","f":""},{"i":"k22","c":"0","n":"ح","s":"خ","l":"","t":"","f":""},{"i":"k23","c":"0","n":"]","s":"}","l":"","t":"","f":""},{"i":"k24","c":"0","n":"[","s":"{","l":"","t":"","f":""},{"i":"k25","c":"0","n":"\\","s":"|","l":"","t":"","f":""},{"i":"k26","c":"0","n":"م","s":"ژ","l":"","t":"","f":""},{"i":"k27","c":"0","n":"و","s":"ز","l":"","t":"","f":""},{"i":"k28","c":"0","n":"ر","s":"ڑ","l":"","t":"","f":""},{"i":"k29","c":"0","n":"ن","s":"ں","l":"","t":"","f":""},{"i":"k30","c":"0","n":"ل","s":"ۂ","l":"","t":"","f":""},{"i":"k31","c":"0","n":"ہ","s":"ء","l":"","t":"","f":""},{"i":"k32","c":"0","n":"ا","s":"آ","l":"","t":"","f":""},{"i":"k33","c":"0","n":"ک","s":"گ","l":"","t":"","f":""},{"i":"k34","c":"0","n":"ی","s":"ي","l":"","t":"","f":""},{"i":"k35","c":"0","n":"؛","s":":","l":"","t":"","f":""},{"i":"k36","c":"0","n":"'","s":"\"","l":"","t":"","f":""},{"i":"k37","c":"0","n":"ق","s":"‍","l":"","t":"","f":""},{"i":"k38","c":"0","n":"ف","s":"‌","l":"","t":"","f":""},{"i":"k39","c":"0","n":"ے","s":"ۓ","l":"","t":"","f":""},{"i":"k40","c":"0","n":"س","s":"‎","l":"","t":"","f":""},{"i":"k41","c":"0","n":"ش","s":"ؤ","l":"","t":"","f":""},{"i":"k42","c":"0","n":"غ","s":"ئ","l":"","t":"","f":""},{"i":"k43","c":"0","n":"ع","s":"‏","l":"","t":"","f":""},{"i":"k44","c":"0","n":"،","s":">","l":"","t":"","f":""},{"i":"k45","c":"0","n":"۔","s":"<","l":"","t":"","f":""},{"i":"k46","c":"0","n":"/","s":"؟","l":"","t":"","f":""},{"i":"k47","c":"0","n":"\\","s":"|","l":"","t":"","f":""}],"deadkeys":[]});
            KeyBoardModel.init();
            document.querySelectorAll('.editable-field').forEach(item => {
                item.addEventListener('click', e => {
                    const row = e.target.closest('.editable-row');
                    const field = e.target.closest('.editable-field');
                    const text = field.querySelector('.editable-text').value
                    console.log(row)
                    console.log(field)
                    KeyBoardModel.show(row.id, field.dataset.field_id, text);
                    document.querySelector('#editor').focus();
                    document.querySelector('#editor').addEventListener('keypress', e => {
                        if (e.key === 'Enter') {
                            handleSave();
                        }
                    })
                    document.querySelector('.modal-save-changes').addEventListener('click', e => {
                        handleSave();
                    })
                })
            })

            document.querySelectorAll('.editable-field .editable-text').forEach(item => {
                item.addEventListener('keypress', e => {
                    $(`#${e.target.closest('.editable-field').id} input`).setUrduInput();
                    if(e.key === 'Enter' || e.key === 'Tab') {
                        document.querySelector('#current_edited_row_id').value = e.target.closest('.editable-row').id;
                        document.querySelector('#current_edited_field_name').value = e.target.closest('.editable-field').dataset.field_id;
                        ajaxToSaveRow(e.target.closest('.editable-row').id, e.target.closest('.editable-field').dataset.field_id);
                        // handleSave();
                    }
                })
            })

            function handleSave() {
                const newVal = document.querySelector('#editor').value
                const currentRowId = document.querySelector('#current_edited_row_id').value
                console.log(currentRowId)
                const currentRow = document.querySelector(`#editable-row-id-${currentRowId}`)
                console.log(currentRow)
                const currentFieldName = document.querySelector('#current_edited_field_name').value
                KeyBoardModel.save(currentRowId, currentFieldName, newVal);

                console.log(document.querySelector(`#first_name-${currentRowId} .editable-text`).value)
                const firstName = document.querySelector(`#first_name-${currentRowId} .editable-text`).value
                const lastName = document.querySelector(`#last_name-${currentRowId} .editable-text`).value
                const address = document.querySelector(`#address-${currentRowId} .editable-text`).value
                const serialNo = document.querySelector(`#serial_no-${currentRowId} .editable-text`).value
                const familyNo = document.querySelector(`#family_no-${currentRowId} .editable-text`).value
                // console.log(firstName, lastName, address)
                $.ajax({
                    url: "https://vertex.plabesk.com/admin/firebase/update-address-details",
                    data: {
                        first_name: firstName,
                        last_name: lastName,
                        address,
                        serial_no: serialNo,
                        family_no: familyNo,
                        id: currentRowId,
                        "_token": "{{ csrf_token() }}",
                    },
                    type: "post",
                    success: res => {
                        console.log(res)
                        if(res && res === '1') {
                            const f = document.querySelector(`.editable-field[id="${currentFieldName}-${currentRowId}"]`)
                            if(f) {
                                f.classList.remove('orange')
                                f.classList.add('green')
                            }
                        }
                    }
                })
                document.querySelector(`#first_name-${currentRowId} .editable-text`).focus();
                KeyBoardModel.hide();
            }

            function ajaxToSaveRow(currentRowId, currentFieldName) {
                const firstName = document.querySelector(`#first_name-${currentRowId} .editable-text`).value
                const lastName = document.querySelector(`#last_name-${currentRowId} .editable-text`).value
                const address = document.querySelector(`#address-${currentRowId} .editable-text`).value
                const serialNo = document.querySelector(`#serial_no-${currentRowId} .editable-text`).value
                const familyNo = document.querySelector(`#family_no-${currentRowId} .editable-text`).value

                $.ajax({
                    url: "https://vertex.plabesk.com/admin/firebase/update-address-details",
                    data: {
                        first_name: firstName,
                        last_name: lastName,
                        address,
                        serial_no: serialNo,
                        family_no: familyNo,
                        id: currentRowId,
                        "_token": "{{ csrf_token() }}",
                    },
                    type: "post",
                    success: res => {
                        console.log(res)
                        if(res && res === '1') {
                            const f = document.querySelector(`.editable-field[id="${currentFieldName}-${currentRowId}"]`)
                            if(f) {
                                f.classList.remove('orange')
                                f.classList.add('green')
                            }
                        }
                    }
                })
            }

            document.querySelectorAll('.keyboard-shortcuts > span').forEach(item => {
                item.addEventListener('click', e => {
                    const t = e.target.textContent
                    if(t) {
                        document.querySelector('#editor').value += t;
                        document.querySelector('#editor').focus();
                    }
                })
            })
        })
    })(jQuery);
</script>
</body>
</html>