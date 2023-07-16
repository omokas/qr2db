<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
	
}
?>
<html encoding="utf-8" manifest="/doesnt-exist.appcache"><head>
        <title>ID Scanner</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- SEO -->
	<meta name="distribution" content="GLOBAL" />
	<meta name="rating" content="General" />
	<meta name="language" content="en" />
	<meta name="author" content="HMU Epictetus Team">
	<meta name="description" content="Scan QR codes and send the result via PHP to Database">
	<meta name="keywords" content="QR,IoT,PHP,Database,MariaDB,code,scanner"/>
	<meta name="news_keywords" content="QR,IoT,PHP,Database,MariaDB,code,scanner"/>

        <!-- qr code scanning library: https://rawgit.com/schmich/instascan-builds/master/instascan.min.js -->
        <script type="text/javascript" src="./js/instascan.min.js"></script>

        <!-- javascript helper library: https://code.jquery.com/jquery-3.3.1.min.js -->
        <script src="./js/jquery-3.3.1.min.js" type="text/javascript"></script>

        <!-- ui via bootstrap -->
        <!-- https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js -->
        <script src="./js/bootstrap.min.js" type="text/javascript"></script>
        
        <!-- https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">

        <!-- webrtc: https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js -->
        <script type="text/javascript" src="./js/adapter.min.js"></script>

        <!-- our own stylesheet definitions -->
        <style>
                body {
                    background: #F5F5F5;
                    
                }

                label{
                        color: #2D7B93;
                        font-weight: bold;
                }

                h1 {
                    color: #a6a6a6;
                }
				
				.form-control {
					width: 400px;
				}
                
                .hspacer {
                    width: 40px; /* define margin as you see fit */
                }

        </style>

        <!-- Javascript Code -->
        <script type="text/javascript">

                
                // Global variables
                var availablecameras = {};

                //------------------------- RANDOM HELPER SCRIPTS ----------------------
                function urlParamOrDefault(name, defaultvalue)
                {
                        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                        if (results == null)
                        {
                                return defaultvalue;
                        }
                        else
{
                                return decodeURI(results[1]) || 0;
                        }
                }
                
                // Sets the initial Values for the GUI controls
                function setDefaultValues(tgtstate)     
                {
                        urlParamOrDefault("")
						var professor_id = <?php echo json_encode($_SESSION['name'], JSON_HEX_TAG); ?>; //get profesor id
						var lesson_id = <?php echo json_encode($_SESSION['lesson'], JSON_HEX_TAG); ?>; //get lesson id
						

                        $("#professor_id").val(professor_id);
                        $("#lesson_id").val(lesson_id);
                }
				
				function beep() {
					var snd = new  Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8avaIf5SvL7pndPvPpndJR9Kuu8fePvuiuhorgWjp7Mf/PRjxcFCPDkW31srioCExivv9lcwKEaHsf/7ow2Fl1T/9RkXgEhYElAoCLFtMArxwivDJJ+bR1HTKJdlEoTELCIqgEwVGSQ+hIm0NbK8WXcTEI0UPoa2NbG4y2K00JEWbZavJXkYaqo9CRHS55FcZTjKEk3NKoCYUnSQ0rWxrZbFKbKIhOKPZe1cJKzZSaQrIyULHDZmV5K4xySsDRKWOruanGtjLJXFEmwaIbDLX0hIPBUQPVFVkQkDoUNfSoDgQGKPekoxeGzA4DUvnn4bxzcZrtJyipKfPNy5w+9lnXwgqsiyHNeSVpemw4bWb9psYeq//uQZBoABQt4yMVxYAIAAAkQoAAAHvYpL5m6AAgAACXDAAAAD59jblTirQe9upFsmZbpMudy7Lz1X1DYsxOOSWpfPqNX2WqktK0DMvuGwlbNj44TleLPQ+Gsfb+GOWOKJoIrWb3cIMeeON6lz2umTqMXV8Mj30yWPpjoSa9ujK8SyeJP5y5mOW1D6hvLepeveEAEDo0mgCRClOEgANv3B9a6fikgUSu/DmAMATrGx7nng5p5iimPNZsfQLYB2sDLIkzRKZOHGAaUyDcpFBSLG9MCQALgAIgQs2YunOszLSAyQYPVC2YdGGeHD2dTdJk1pAHGAWDjnkcLKFymS3RQZTInzySoBwMG0QueC3gMsCEYxUqlrcxK6k1LQQcsmyYeQPdC2YfuGPASCBkcVMQQqpVJshui1tkXQJQV0OXGAZMXSOEEBRirXbVRQW7ugq7IM7rPWSZyDlM3IuNEkxzCOJ0ny2ThNkyRai1b6ev//3dzNGzNb//4uAvHT5sURcZCFcuKLhOFs8mLAAEAt4UWAAIABAAAAAB4qbHo0tIjVkUU//uQZAwABfSFz3ZqQAAAAAngwAAAE1HjMp2qAAAAACZDgAAAD5UkTE1UgZEUExqYynN1qZvqIOREEFmBcJQkwdxiFtw0qEOkGYfRDifBui9MQg4QAHAqWtAWHoCxu1Yf4VfWLPIM2mHDFsbQEVGwyqQoQcwnfHeIkNt9YnkiaS1oizycqJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
					snd.play();
				}

                // on Load Eventhandler
                $(window).on('load',function(){

                        // Set Default Values for all Fields
                        setDefaultValues();
                        
                        // *** Init QR Code Stuff ***--
                        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
                        scanner.addListener('scan', function (content) {
								var professor_id = <?php echo json_encode($_SESSION['name'], JSON_HEX_TAG); ?>; //Current connected User ID
								var lesson_id = <?php echo json_encode($_SESSION['lesson'], JSON_HEX_TAG); ?>;  //Current Lesson ID
								var student_id = content; 		//Currently Student ID - Scanned QR data
								var device_id = "web_client";	// web_client | ESP_client
								
                                // Log scanned QR data to console for debug purposes
                                console.log("Scanned QR Code: " + content);

                                //Write scanned data to list
                                $("#latestScan").val("Read tag " + content + " lesson: " + lesson_id + "\n" + "Time: " + new Date().toISOString());
								
								//Send data to database through php script 
								var url = "http://orestis-iot.ddns.net:9000/id_scanner/" + "send_to_db.php?" + "student_id=" + student_id + "&lesson_id=" + lesson_id + "&device_id=" + device_id;
								fetch(url)
								.then(function() {
									// handle the response
									console.log("Request to DB - OK");
								})
								.catch(function() {
									// handle the error
								});
								
								
								//Note
								//We don't parse datetime using javascript because the device executing it might have wrong datetime
								//Datetime is instead parsed in PHP server-side every time the above URL is executed which we assume that it always has correct time
								
								//Beep to inform successfull read
								beep();
                                
                        });
                        
                        // List the available cameras
                        Instascan.Camera.getCameras().then(function (cameras) 
                        {
                                selectedcamera = null;
                                // Multiple cameras found - let the user select
                                $.each(cameras, (i, c) => {

                                        // Buid a meaningful name
                                        camname = "Cam#" + (i+1);
                                        if(c.name != null && c.name.length > 0)
                                        {
                                                camname = c.name;
                                        }
                                        
                                        // Remember the camera (normal)
                                        availablecameras[camname] = {};
                                        availablecameras[camname].instacam = c;
                                        availablecameras[camname].mirrored = false;

                                        // And add a button for it
                                        availablecameras[camname].button = $("<button/>").text(camname)
                                                                                .attr({ type: "button", class: "btn btn-primary cambtn", value:camname})
                                                                                .appendTo("#availablecams");
                                        
                                        // add a spacer
                                        $("<span/>").attr({class:"hspacer"}).appendTo("#availablecams");

                                        // Remember the mirrored version of the same camera
                                        camname = camname + " (mirrored)";
                                        availablecameras[camname] = {};
                                        availablecameras[camname].instacam = c;
                                        availablecameras[camname].mirrored = true;
                                        availablecameras[camname].button = $("<button/>").text(camname)
                                                                                .attr({ type: "button", class: "btn btn-primary cambtn", value:camname})
                                                                                .appendTo("#availablecams");
                                        // add a new line
                                        $("<div/>").appendTo("#availablecams");
                                });
        
                                // Select the best button (back facing without mirroring for mobile phones)
                                defaultcam = null;
                                $.each(availablecameras, function(index, cam){
                                        btn = cam.button;
                                        caption = btn.val().toLowerCase(); 
                                        if(caption.includes("back") == true && caption.includes("mirror") == false)
                                        {
                                                defaultcam = cam;
                                        }
                                });
                                
                                // if we have not found a camers we take the first that we find
                                if(defaultcam == null)
                                {
                                       defaultcam = Object.values(availablecameras)[0];
                                }
                                
                                // ... and select it
                                defaultcam.button.click();

                        }).catch(function (e) {
                                console.error(e);
                        });
                        

                        // Eventhandler for camera selection
                        $('#availablecams').on('click', '.cambtn', function(btn) {
                                cam = availablecameras[btn.target.value];
                                scanner.mirror = cam.mirrored;
                                scanner.start(cam.instacam);
                        });


                });

        </script>

</head><body>

<h1>HMU ID Scanner - Beta</h1>
<div class="row">
        <div class="col-sm-6">
                <form>
                                                <label for="professor_id">Professor ID</label>
                                                <input type="text" id="professor_id">
												<br><br>
                                                <label for="lesson_id">Lesson ID</label>
                                                <input type="text" id="lesson_id">
												<br><br>
                                                <label for="latestScan">Latest local Scan</label>
                                                <textarea class="form-control" id="latestScan" rows="10" cols="40"></textarea>
                </form>

        </div>
        <!-- Camera preview -->
        <div class="col-sm-6">
                <video id="preview">Select a camera ...</video>
                <div id="availablecams" class="text-center"></div>
        </div>
</div> 
<p>
HMU Epictetus Team - (c) 2023 - Forked from <a href="https://github.com/bytebang/qr2mqtt">qr2mqtt</a><br>
Modified for HMU ID Scanner usage. Beta version
</p>
</body>
</html>