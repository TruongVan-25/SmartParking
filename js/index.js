var button_light_on = document.getElementById("light_on");
var button_light_off = document.getElementById("light_off");
var button_start_stream = document.getElementById("start_stream_video");
var button_stop_stream = document.getElementById("stop_stream_video");
var button_start_detection = document.getElementById("start_detection");
var button_stop_detection = document.getElementById("stop_detection");
var button_start_sensor = document.getElementById("start_sensor");
var button_stop_sensor = document.getElementById("stop_sensor");

var url_take_photo_link = "", url_video_record_link = "";

button_light_off.disabled = true;
button_stop_stream.disabled = true;
button_stop_detection.disabled = true;
button_stop_sensor.disabled = true;

$(document).ready(function() {
    //Robot Control
    console.log( "read JS file ready!" );
    //$('[data-toggle="tooltip"]').tooltip();   
    //jQuery("[data-toggle="tooltip"]").tooltip();

    $("#robot_forward").mousedown(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/forward.php",
            success: function(result) {
                alert('Go forward!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    }).mouseup(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/stop.php",
            success: function(result) {
                alert('Stop!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#robot_left").mousedown(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/left.php",
            success: function(result) {
                alert('Turn left!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    }).mouseup(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/stop.php",
            success: function(result) {
                alert('Stop!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#robot_right").mousedown(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/right.php",
            success: function(result) {
                alert('Turn right!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    }).mouseup(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/stop.php",
            success: function(result) {
                alert('Stop!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#robot_back").mousedown(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/back.php",
            success: function(result) {
                alert('Turn back!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    }).mouseup(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/stop.php",
            success: function(result) {
                alert('Stop!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })

    $("#robot_stop").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/stop.php",
            success: function(result) {
                alert('Stop car!');
            },
            error: function(result) {
                 alert('can not stop!');
            }
        });
    })

//Camera Control
    // NOTE: we change code because camera physical design changed
    $("#camera_up").click(function(e) {
            e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/camera_up.php",            
            success: function(result){
                alert('add data ok!');
            },           
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#camera_down").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/camera_down.php",
            success: function(result) {
                alert('Turn left!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#camera_right").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/camera_left.php",
            success: function(result) {
                alert('Turn left!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#camera_left").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/camera_right.php",
            // data: {"angleX":"30", "angleY":"90"},
            success: function(result) {
                alert('Turn left!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#camera_center").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/camera_center.php",
            success: function(result) {
                alert('Turn right!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })

    //Control buzzer and light
    $("#buzzer_on").mousedown(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/buzzer_on.php",
            success: function(result) {
                //alert('Turn back!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    }).mouseup(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/buzzer_off.php",
            success: function(result) {
                //alert('Stop!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
    })
    $("#light_on").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/light_on.php",            
            success: function(result){
                //alert('add data ok!');
            },           
            error: function(result) {
                // alert('can not go!');
            }
        });
        button_light_on.disabled = true;
        button_light_off.disabled = false;
    })
    $("#light_off").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "http://172.16.10.21/admin/robot/light_off.php",
            success: function(result) {
                //alert('Turn left!');
            },
            error: function(result) {
                // alert('can not go!');
            }
        });
        button_light_on.disabled = false;
        button_light_off.disabled = true;
    })



    // Control camera action
    $("#start_stream_video").click(function(e) {
        e.preventDefault();
        var r = confirm('Start Video Streaming!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://172.16.10.21/admin/start_stream_video.php",
                //url: "http://localhost/smartparking/admin/start_stream_video.php",
                success: function(result) {
                    alert('Start stream video successfully!');
                    // document.getElementById("video_feed").src="http://192.168.1.112:8000/stream.mjpg";
                    // window.location.reload();
                },
                error: function(result) {
                    // alert('can not start streaming!');
                    // window.location.reload();
                }
                //window.location.reload();
            });
            // $('#stream_windows').load(location.href + ' #stream_windows'); 
            setTimeout(() => { document.getElementById("video_feed").src="http://192.168.1.200/html/"; }, 5000);
            button_stop_stream.disabled = false;
            button_start_stream.disabled = true;
        }
               
    })
    $("#stop_stream_video").click(function(e) {
        e.preventDefault();
        var r = confirm('Stop Video Streaming!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://172.16.10.21/admin/stop_stream_video.php",
                //url: "http://localhost/smartparking/admin/start_stream_video.php",
                success: function(result) {
                    alert('Stop stream video successfully!');
                    window.location.reload();
                },
                error: function(result) {
                    // alert('can not start streaming!');
                    //window.location.reload();
                }
                //window.location.reload();
            });
            button_start_stream.disabled = false;
            button_stop_stream.disabled = true;
        }
        
    })
    $("#take_photo").click(function(e) {
        e.preventDefault();
        if (button_start_detection.disabled == true)
        {
            url_take_photo_link = "http://localhost/smartparking/admin/take_photo_detection.php";
        }
        else{
            url_take_photo_link = "http://localhost/smartparking/admin/take_photo_normal.php";
        }
        var r = confirm('Start take photo!');
        if (r == true) {
            $.ajax({
                type: "GET",
                // url: "http://localhost/smartparking/admin/take_photo.php",
                url: url_take_photo_link,
                success: function(result) {
                    alert('Take photo successfully!');
                    // window.location.reload();
                },
                error: function(result) {
                    //alert('can not take photo!');
                }
                //window.location.reload();
            });
        }
        
    })
    $("#video_record").click(function(e) {
        e.preventDefault();
        if (button_start_detection.disabled == true)
        {
            url_video_record_link = "http://localhost/smartparking/admin/video_record_detection.php";
        }
        else{
            url_video_record_link = "http://localhost/smartparking/admin/video_record.php";
        }
        var r = confirm('Start video record!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: url_video_record_link,
                success: function(result) {
                    alert('Video record successfully!');
                    //window.location.reload();
                },
                error: function(result) {
                    //alert('can not record video!');
                }
                //window.location.reload();
            });
        }
    })
    $("#start_sensor").click(function(e) {
        e.preventDefault();
        var r = confirm('Start sensor!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://172.16.10.21/admin/sensor/start_sensor.php",                
                success: function(result) {
                    alert('Start sensor successfully!');
                },
                error: function(result) {
                }                
            });
            button_start_sensor.disabled = true;
            button_stop_sensor.disabled = false;
        }
        
    })
    $("#stop_sensor").click(function(e) {
        e.preventDefault();
        var r = confirm('Stop sensor!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://172.16.10.21/admin/sensor/stop_sensor.php",                
                success: function(result) {
                    alert('Stop sensor successfully!');
                },
                error: function(result) {
                }                
            });
            button_start_sensor.disabled = false;
            button_stop_sensor.disabled = true;
        }
        
    })
    $("#start_detection").click(function(e) {
        e.preventDefault();
        var r = confirm('Start human detection!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://localhost/smartparking/admin/start_detection.php",
                //url: "http://localhost/smartparking/admin/start_stream_video.php",
                success: function(result) {
                    // alert('Human detection successfully!');
                    // document.getElementById("video_feed").src = "http://localhost:8000/video_feed";
                    //window.location.reload();
                },
                error: function(result) {
                    // alert('can not start streaming!');
                    //window.location.reload();
                }
                //window.location.reload();
            });
            setTimeout(() => { document.getElementById("video_feed").src = "http://localhost:8000/video_feed"; }, 10000);
            // document.getElementById("video_feed").src = "http://localhost:8000/video_feed";
            button_start_detection.disabled = true;
            button_stop_detection.disabled = false;
        }
        
    })
    $("#stop_detection").click(function(e) {
        e.preventDefault();
        var r = confirm('Stop human detection!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://localhost:8000/stop_detector",
                //url: "http://localhost/smartparking/admin/start_stream_video.php",
                success: function(result) {
                    alert('Stop Human detection successfully!');
                    
                    //window.location.reload();
                },
                error: function(result) {
                    // alert('can not start streaming!');
                    //window.location.reload();
                }
                //window.location.reload();
            });
            document.getElementById("video_feed").src="http://172.16.10.21:8000/stream.mjpg";
            button_start_detection.disabled = false;
            button_stop_detection.disabled = true;
        }
       
    })

// System control 
    $("#shutdown_pi").click(function(e) {
        e.preventDefault();        
        var r = confirm('Shutdown system, are you sure?!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://172.16.10.21/admin/shutdown_pi.php",
                //url: "http://localhost/smartparking/admin/start_stream_video.php",
                success: function(result) {
                    alert('shutdown_pi successfully!');
                    //window.location.reload();
                },
                error: function(result) {
                    // alert('can not start streaming!');
                    //window.location.reload();
                }
                //window.location.reload();
            }); 
        }
    })
    
    $("#restart_pi").click(function(e) {
        e.preventDefault(); 
        var r = confirm('Restart system, are you sure?!');
        if (r == true) {
            $.ajax({
                type: "GET",
                url: "http://192.168.1.112/admin/restart_pi.php",
                //url: "http://localhost/smartparking/admin/start_stream_video.php",
                success: function(result) {
                    alert('restart_pi successfully!');
                    window.location.reload();
                },
                error: function(result) {
                    // alert('can not start streaming!');
                    //window.location.reload();
                }
                //window.location.reload();
            }); 
        }        
        
    });
    
});



// For access to web page
// document.getElementById('goMonitor').onclick = function () {
//     window.location = 'data.php';
// }
// document.getElementById('showDataTable').onclick = function () {
//     window.location = 'displaying_data.php';
//     // alert('can not go!');
// }
// // document.getElementById('robot_forward').onclick = function () {
// //     window.location = 'http://172.16.10.21/forward.php';
// // }
// // document.getElementById('robot_stop').onclick = function () {
// //     window.location = 'http://172.16.10.21/stop.php';
// // }
// // Test function mouse down & up
// function mouseDown() {
//   document.getElementById("camera_control").style.color = "red";
// }

// function mouseUp() {
//   document.getElementById("camera_control").style.color = "green";
// }
