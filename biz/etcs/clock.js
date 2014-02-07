function clock()
{
    var hours = (new Date()).getHours ();
    var minutes = (new Date()).getMinutes ();
    var seconds = (new Date()).getSeconds ();
    var m;
    (new Date()).setSeconds (seconds + 1);
    var day = (new Date()).getDate ();
    if (hours == 0) hours = "12";
    if (minutes <= 9) minutes = "0" + minutes;
    if (seconds <= 9) seconds = "0" + seconds;
    m = (new Date()).getMonth ();

    showDate = day+" "+month[m]+" "+(new Date()).getFullYear();
    showTime = hours+":"+minutes+":"+seconds;
    if (document.layers) {
        document.layers.dispDate.document.write (showDate);
        document.layers.dispDate.document.close ();
        document.layers.dispTime.document.write (showTime);
        document.layers.dispTime.document.close ();
    }
    else if (document.all) {
        dispDate.innerHTML = showDate;
        dispTime.innerHTML = showTime;
    }
    else {
        document.getElementById("dispDate").innerHTML = showDate;
        document.getElementById("dispTime").innerHTML = showTime;
    }
    setTimeout ("clock()", 1000);
} 