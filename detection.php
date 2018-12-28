<?php
//Starting a session for this user
$a = session_id();
if ( empty( $a ) ) {
	session_start();
}


?>
<!doctype html>
<html>

<head>
    <title>Detection</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Detection">
    <meta name="author" content="Hussein Bakri">
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/CustomCSS.css">

    <!-- Bootstrap Theme CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">

    <!-- JQuery library -->
    <script type="text/javascript" src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Include my custom-download speed logic-->
    <!-- NecessaryInclude is speedtest_worker JS-->
    <!--    <script src="speed/hb-custom-speed.js"></script>-->


    <!--  Fingerprinting Library used-->
    <script src="js/client.min.js"></script>

    <!-- Include WebGL Report JS-->
    <!--<script src="js/webglreport.js"></script>-->

</head>

<body onload="startStop();">

<!-- Page Content -->
<div class="container">


    <input type="hidden" id="download" name="download" value="download" readonly>

    <br/><br/>
    <br/>

    <script type="text/javascript">

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        //For changing test settings
        var download;
        var upload;
        var jitter;
        var ip;
        var ping;
        var w = null; //speedtest worker
        // var parameters = { //custom test parameters.
        //     time_dl: 10, //download test lasts 10 seconds
        //     time_ul: 10, //upload test lasts 10 seconds
        //     count_ping: 20, //ping+jitter test does 20 pings
        //     getIp_ispInfo: false //will only get IP address without ISP info
        // };

        var parameters = { //custom test parameters. See doc.md for a complete list
            test_order: "D",    //Only Download Test
            time_dl: 10 //download test lasts 10 seconds
            //time_ul: 10 //upload test lasts 10 seconds
            // count_ping: 20, //ping+jitter test does 20 pings
            // getIp_ispInfo: false //will only get IP address without ISP info
        };

        function startStop() {
            if (w != null) {
                //speedtest is running, abort
                w.postMessage('abort');
                w = null;
                initUI();
                console.log("Initialized");

            } else {
                //test is not running, begin
                w = new Worker('./speed/speedtest_worker.min.js');
                //run the detection with what parameters we set
                w.postMessage('start ' + JSON.stringify(parameters));
                //I("startStopBtn").className = "running";
                w.onmessage = function (e) {
                    var data = e.data.split(';');
                    var status = Number(data[0]);
                    if (status >= 4) {
                        //test completed
                        w = null;
                    }
                    //I("ip").textContent = data[4];
                    //var ip = data[4];
                    //I("dlText").textContent = (status == 1 && data[1] == 0) ? "..." : data[1];
                    $('#download').val((status == 1 && data[1] == 0) ? "..." : data[1]);
                    //download = (status == 1 && data[1] == 0) ? "..." : data[1];
                    // I("ulText").textContent = (status == 3 && data[2] == 0) ? "..." : data[2];
                    //var upload = (status == 3 && data[2] == 0) ? "..." : data[2];

                    //I("pingText").textContent = data[3];
                    //var ping = = data[3];

                    // I("jitText").textContent = data[5];
                    //var jitter = data[5];

                };
            }
            // var downloadSpeed = $('#download').value;

        }

        //poll the status from the worker every 200ms (this will also update the UI)
        setInterval(function () {
            if (w) {
                w.postMessage('status');
            }

        }, 200);

        //function to (re)initialize UI
        function initUI() {
            // I("dlText").textContent = "";
            download = null;
            //I("ulText").textContent = "";
            //I("pingText").textContent = "";
            //I("jitText").textContent = "";
            //I("ip").textContent = "";
        }

    </script>


    <script type="text/javascript">initUI();</script>


    <canvas id="myCanvas" width="1" height="1"></canvas>
    <script>

        //*********** Getting WebGL Parameters from Extensions *************

        // Code to get Max Anisotropy
        function getMaxAnisotropy(gl) {
            var e = gl.getExtension('EXT_texture_filter_anisotropic')
                || gl.getExtension('WEBKIT_EXT_texture_filter_anisotropic')
                || gl.getExtension('MOZ_EXT_texture_filter_anisotropic');

            if (e) {
                var max = gl.getParameter(e.MAX_TEXTURE_MAX_ANISOTROPY_EXT);
                // See Canary bug: https://code.google.com/p/chromium/issues/detail?id=117450
                if (max === 0) {
                    max = 2;
                }
                return max;
            }
            return 'n/a';
        }

        //Code to get the Max Color Buffers from WEBGL_draw_buffers extension
        function getMaxColorBuffers(gl) {
            var maxColorBuffers = 1;
            var ext = gl.getExtension("WEBGL_draw_buffers");
            if (ext != null)
                maxColorBuffers = gl.getParameter(ext.MAX_DRAW_BUFFERS_WEBGL);

            return maxColorBuffers;
        }

        function getWebGLandReturnObj() {


            var canvas = document.getElementById('myCanvas');
            var gl = canvas.getContext('webgl', {stencil: true});
            var glVersion = gl.getParameter(gl.VERSION);
            var glSHADING_LANGUAGE_VERSION = gl.getParameter(gl.VERSION);
            var Antialiasing = gl.getContextAttributes().antialias;
            //Code to get the Major Performance Caveat
            //var AttributeOBJ = gl.getContextAttributes();
            var debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            var vendor = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
            var GPUrenderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);

            //#### Vertex Shader WebGL Parameters ####
            var glMAX_VERTEX_ATTRIBS = gl.getParameter(gl.MAX_VERTEX_ATTRIBS);
            var glMAX_VERTEX_UNIFORM_VECTORS = gl.getParameter(gl.MAX_VERTEX_UNIFORM_VECTORS);
            var glMAX_VERTEX_TEXTURE_IMAGE_UNITS = gl.getParameter(gl.MAX_VERTEX_TEXTURE_IMAGE_UNITS);
            var glMAX_VARYING_VECTORS = gl.getParameter(gl.MAX_VARYING_VECTORS);

            //Best Float Precision Code like Best Float Precision	[-2^127, 2^127] (23)
            var VertexShaderPrecision = gl.getShaderPrecisionFormat(gl.VERTEX_SHADER, gl.HIGH_FLOAT).precision;
            var VertexShaderrangeMax = gl.getShaderPrecisionFormat(gl.VERTEX_SHADER, gl.HIGH_FLOAT).rangeMax;
            var VertexShaderrangeMin = gl.getShaderPrecisionFormat(gl.VERTEX_SHADER, gl.HIGH_FLOAT).rangeMin;
            ///document.writeln(gl.getShaderPrecisionFormat(gl.VERTEX_SHADER,gl.HIGH_FLOAT));
            //#### END OF Vertex Shader WebGL Parameters ####

            //#### Rasterizer WebGL Parameters ####
            var glALIASED_LINE_WIDTH_RANGE = gl.getParameter(gl.ALIASED_LINE_WIDTH_RANGE);
            var glALIASED_POINT_SIZE_RANGE = gl.getParameter(gl.ALIASED_POINT_SIZE_RANGE);
            //#### END OF Rasterizer WebGL Parameters ####


            //#### Fragment Shader WebGL Parameters ####
            var glMAX_FRAGMENT_UNIFORM_VECTORS = gl.getParameter(gl.MAX_FRAGMENT_UNIFORM_VECTORS);
            //Could have values between 1, 8, 16, 32 depending on device & browser
            var glMAX_TEXTURE_IMAGE_UNITS = gl.getParameter(gl.MAX_TEXTURE_IMAGE_UNITS);
            var glFloatPrecision = gl.getShaderPrecisionFormat(gl.FRAGMENT_SHADER, gl.HIGH_FLOAT).precision;
            var glINTPrecision = gl.getShaderPrecisionFormat(gl.FRAGMENT_SHADER, gl.HIGH_INT).precision;
            //Best Float Precision Code like Best Float Precision	[-2^127, 2^127] (23)
            var FragmentShaderPrecision = gl.getShaderPrecisionFormat(gl.FRAGMENT_SHADER, gl.HIGH_FLOAT).precision;
            var FragmentShaderrangeMax = gl.getShaderPrecisionFormat(gl.FRAGMENT_SHADER, gl.HIGH_FLOAT).rangeMax;
            var FragmentShaderrangeMin = gl.getShaderPrecisionFormat(gl.FRAGMENT_SHADER, gl.HIGH_FLOAT).rangeMin;
            //#### END OF Fragment Shader WebGL Parameters ####


            //#### Framebuffer WebGL Parameters ####
            var glMax_Color_Buffers = getMaxColorBuffers(gl);
            //document.writeln(gl.getShaderPrecisionFormat(gl.FRAGMENT_SHADER,gl.HIGH_FLOAT));
            var glRGBA_Bits = [gl.getParameter(gl.RED_BITS), gl.getParameter(gl.GREEN_BITS), gl.getParameter(gl.BLUE_BITS), gl.getParameter(gl.ALPHA_BITS)];
            var glDepth_Bits = gl.getParameter(gl.DEPTH_BITS);
            var glStencil_Bits = gl.getParameter(gl.STENCIL_BITS);
            var glMAX_RENDERBUFFER_SIZE = gl.getParameter(gl.MAX_RENDERBUFFER_SIZE);
            var glMAX_VIEWPORT_DIMS = gl.getParameter(gl.MAX_VIEWPORT_DIMS);
            //#### END OF Framebuffer WebGL Parameters ####


            //#### TexturesWebGL Parameters ####
            var glMAX_TEXTURE_SIZE = gl.getParameter(gl.MAX_TEXTURE_SIZE);
            var glMAX_CUBE_MAP_TEXTURE_SIZE = gl.getParameter(gl.MAX_CUBE_MAP_TEXTURE_SIZE);
            var glMAX_COMBINED_TEXTURE_IMAGE_UNITS = gl.getParameter(gl.MAX_COMBINED_TEXTURE_IMAGE_UNITS);
            var glMax_Anisotropy = getMaxAnisotropy(gl);
            //#### END OF TexturesWebGL Parameters ####

            //#### Extensions ####
            var glSupported_Extensions = gl.getSupportedExtensions();
            //#### END OF Extensions ####

            var gl2 = canvas.getContext('webgl2');

            //creating a JavaScript Object and putting parameters in it
            // var ClientParameters = {};
            var ClientParameters = new Object;
            ClientParameters['glVersion'] = glVersion;
            ClientParameters['glSHADING_LANGUAGE_VERSION'] = glSHADING_LANGUAGE_VERSION;
            ClientParameters['Antialiasing'] = Antialiasing;
            ClientParameters['vendor'] = vendor;
            ClientParameters['GPUrenderer'] = GPUrenderer;
            ClientParameters['glMAX_VERTEX_ATTRIBS'] = glMAX_VERTEX_ATTRIBS;
            ClientParameters['glMAX_VERTEX_UNIFORM_VECTORS'] = glMAX_VERTEX_UNIFORM_VECTORS;
            ClientParameters['glMAX_VERTEX_TEXTURE_IMAGE_UNITS'] = glMAX_VERTEX_TEXTURE_IMAGE_UNITS;
            ClientParameters['glMAX_VARYING_VECTORS'] = glMAX_VARYING_VECTORS;
            ClientParameters['VertexShaderPrecision'] = VertexShaderPrecision;
            ClientParameters['VertexShaderrangeMax'] = VertexShaderrangeMax;
            ClientParameters['VertexShaderrangeMin'] = VertexShaderrangeMin;
            ClientParameters['glALIASED_LINE_WIDTH_RANGE'] = glALIASED_LINE_WIDTH_RANGE;
            ClientParameters['glALIASED_POINT_SIZE_RANGE'] = glALIASED_POINT_SIZE_RANGE;
            ClientParameters['glMAX_FRAGMENT_UNIFORM_VECTORS'] = glMAX_FRAGMENT_UNIFORM_VECTORS;
            ClientParameters['glMAX_TEXTURE_IMAGE_UNITS'] = glMAX_TEXTURE_IMAGE_UNITS;
            ClientParameters['glFloatPrecision'] = glFloatPrecision;
            ClientParameters['glINTPrecision'] = glINTPrecision;
            ClientParameters['FragmentShaderPrecision'] = FragmentShaderPrecision;
            ClientParameters['FragmentShaderrangeMax'] = FragmentShaderrangeMax;
            ClientParameters['FragmentShaderrangeMin'] = FragmentShaderrangeMin;
            ClientParameters['glRGBA_Bits'] = glRGBA_Bits;
            ClientParameters['glDepth_Bits'] = glDepth_Bits;
            ClientParameters['glStencil_Bits'] = glStencil_Bits;
            ClientParameters['glMAX_RENDERBUFFER_SIZE'] = glMAX_RENDERBUFFER_SIZE;
            ClientParameters['glMAX_VIEWPORT_DIMS'] = glMAX_VIEWPORT_DIMS;
            ClientParameters['glMAX_TEXTURE_SIZE'] = glMAX_TEXTURE_SIZE;
            ClientParameters['glMAX_CUBE_MAP_TEXTURE_SIZE'] = glMAX_CUBE_MAP_TEXTURE_SIZE;
            ClientParameters['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] = glMAX_COMBINED_TEXTURE_IMAGE_UNITS;
            ClientParameters['glMax_Color_Buffers'] = glMax_Color_Buffers;
            ClientParameters['glMax_Anisotropy'] = glMax_Anisotropy;
            ClientParameters['glSupported_Extensions'] = glSupported_Extensions;


            console.log(ClientParameters);


            return ClientParameters;

        }


        function getFingerPrintJS() {
            // Create a new ClientJS object
            var client = new ClientJS();

            // Get the client's fingerprint id
            var fingerprint = client.getFingerprint();

            var FingerPrintObj = new Object;

            // Print the 32bit hash id to the console
            FingerPrintObj['fingerprint'] = fingerprint;
            FingerPrintObj['getBrowserData'] = client.getBrowserData();
            FingerPrintObj['getFingerprint'] = client.getFingerprint();
            FingerPrintObj['getUserAgent'] = client.getUserAgent();
            FingerPrintObj['getUserAgentLowerCase'] = client.getUserAgentLowerCase();
            FingerPrintObj['getBrowser'] = client.getBrowser();
            FingerPrintObj['getBrowserVersion'] = client.getBrowserVersion();
            FingerPrintObj['getBrowserMajorVersion'] = client.getBrowserMajorVersion();
            FingerPrintObj['isIE'] = client.isIE();
            FingerPrintObj['isChrome'] = client.isChrome();
            FingerPrintObj['isFirefox'] = client.isFirefox();
            FingerPrintObj['isSafari'] = client.isSafari();
            FingerPrintObj['isOpera'] = client.isOpera();
            FingerPrintObj['getEngine'] = client.getEngine();
            FingerPrintObj['getEngineVersion'] = client.getEngineVersion();
            FingerPrintObj['getOS'] = client.getOS();
            FingerPrintObj['getOSVersion'] = client.getOSVersion();
            FingerPrintObj['isWindows'] = client.isWindows();
            FingerPrintObj['isMac'] = client.isMac();
            FingerPrintObj['isLinux'] = client.isLinux();
            FingerPrintObj['isUbuntu'] = client.isUbuntu();
            FingerPrintObj['isSolaris'] = client.isSolaris();
            FingerPrintObj['getDevice'] = client.getDevice();
            FingerPrintObj['getDeviceType'] = client.getDeviceType();
            FingerPrintObj['getDeviceVendor'] = client.getDeviceVendor();
            FingerPrintObj['getCPU'] = client.getCPU();
            FingerPrintObj['isMobile'] = client.isMobile();
            FingerPrintObj['isMobileMajor'] = client.isMobileMajor();
            FingerPrintObj['isMobileAndroid'] = client.isMobileAndroid();
            FingerPrintObj['isMobileOpera'] = client.isMobileOpera();
            FingerPrintObj['isMobileWindows'] = client.isMobileWindows();
            FingerPrintObj['isMobileBlackBerry'] = client.isMobileBlackBerry();
            FingerPrintObj['isMobileIOS'] = client.isMobileIOS();
            FingerPrintObj['isIphone'] = client.isIphone();
            FingerPrintObj['isIpad'] = client.isIpad();
            FingerPrintObj['isIpod'] = client.isIpod();
            FingerPrintObj['getScreenPrint'] = client.getScreenPrint();
            FingerPrintObj['getColorDepth'] = client.getColorDepth();
            FingerPrintObj['getCurrentResolution'] = client.getCurrentResolution();
            FingerPrintObj['getAvailableResolution'] = client.getAvailableResolution();
            FingerPrintObj['getDeviceXDPI'] = client.getDeviceXDPI();
            FingerPrintObj['getDeviceYDPI'] = client.getDeviceYDPI();
            FingerPrintObj['getPlugins'] = client.getPlugins();
            FingerPrintObj['isJava'] = client.isJava();
            FingerPrintObj['getJavaVersion'] = client.getJavaVersion();
            FingerPrintObj['isFlash'] = client.isFlash();
            FingerPrintObj['getFlashVersion'] = client.getFlashVersion();
            FingerPrintObj['isSilverlight'] = client.isSilverlight();
            FingerPrintObj['getSilverlightVersion'] = client.getSilverlightVersion();
            FingerPrintObj['getMimeTypes'] = client.getMimeTypes();
            FingerPrintObj['isMimeTypes'] = client.isMimeTypes();
            FingerPrintObj['isFont'] = client.isFont();
            FingerPrintObj['getFonts'] = client.getFonts();
            FingerPrintObj['isLocalStorage'] = client.isLocalStorage();
            FingerPrintObj['isSessionStorage'] = client.isSessionStorage();
            FingerPrintObj['isCookie'] = client.isCookie();
            FingerPrintObj['getTimeZone'] = client.getTimeZone();
            FingerPrintObj['getLanguage'] = client.getLanguage();
            FingerPrintObj['getSystemLanguage'] = client.getSystemLanguage();
            FingerPrintObj['isCanvas'] = client.isCanvas();
            FingerPrintObj['getCanvasPrint'] = client.getCanvasPrint();

            console.log(FingerPrintObj);


            return FingerPrintObj;
        }


        async function sendAjax() {

            //WebGL Params
            var ClientCapabilities = new Object;

            ClientCapabilities = getWebGLandReturnObj();

            //FingerPrintJS
            var fingerprintjs = new Object;

            fingerprintjs = getFingerPrintJS();

            //Getting download =
            await sleep(15000);
            //var d = getDownload();
            var d = $('#download').val();
            console.log("Download: " + d);


            //Sending the ClientParameters Object to adaptiveWithInference.php
            //The JSON.stringify() method converts a JavaScript value to a JSON string,
            var ClientParametersStringfied = JSON.stringify(ClientCapabilities);

            //Sending the fingerprintjs Object to adaptiveWithInference.php
            //The JSON.stringify() method converts a JavaScript value to a JSON string,
            var fingerprintjsStringfied = JSON.stringify(fingerprintjs);


            $.ajax({
                method: "POST",
                url: "processingpages/ClientCapabilitiesHandler.php",
                data: {
                    Client_Capabilities: ClientParametersStringfied,
                    fingerprint: fingerprintjsStringfied,
                    download: d
                }
            }).done(function (msg) {
                //alert( "Data Sent: " + msg );
            });


        }

        $(document).ready(function () {
            console.log("Document Ready funct");
            $('#Status').html('Please wait for the detection component to detect the capability of your device...Once finished you will be able to choose a model');


            //calling sendAjax()
            sendAjax();

        });


    </script>

</div>
<!-- container -->

<?php
//$something = $_POST['WebGLObj'];
//echo $something;
//header('Content-Type: application/json');

//var_dump($_REQUEST);
if ( isset( $_POST['WebGLObj'] ) ) {
	$_SESSION['WebGLParamsS'] = $_POST['WebGLObj'];
	var_dump( $_SESSION['WebGLParamsS'] );
}
//print json_encode(array('message' => $_SESSION['ClientParameters']));
?>

</body>

</html>
