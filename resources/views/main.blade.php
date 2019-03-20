
{{--* Created by PhpStorm.--}}
{{--* User: macbookpro--}}
{{--* Date: 2019-03-17--}}
{{--* Time: 18:12--}}

<!DOCTYPE html>
<html lang="v">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/87/three.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="{{ asset('js/Detector.js') }}"></script>
    <script src="{{ asset('js/OrbitControls.js') }}"></script>
    <script src="{{ asset('js/OBJLoader.js') }}"></script>
    <script src="{{ asset('js/MTLLoader.js') }}"></script>

    <style>
        body {
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
    </style>
    <title></title>

</head>
<body>

<h1 style="text-align: center;">Door - 3D Viewer</h1>
<span onClick="zoomIn()"><i class="fas fa-plus-circle fa-3x"></i></span>
<span onClick="zoomOut()"><i class="fas fa-minus-circle fa-3x"></i></span>

<script>
    // All of these variables will be needed later, just ignore them for now.
    var container;
    var camera, controls, scene, renderer;
    var lighting, ambient, keyLight, fillLight, backLight;
    var windowHalfX = window.innerWidth / 2;
    var windowHalfY = window.innerHeight / 2 - 100;

    init();
    animate();

    function init() {
        zoomscale = 1;
        //init container contain renderer
        mainDiv = document.createElement('div');
        container = document.createElement('div');
        container.className = "container";
        mainDiv.appendChild(container);
        document.body.appendChild(mainDiv);

        //init camera
        camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 10000);
        camera.position.z = 5000;

        //Scene holds camera
        scene = new THREE.Scene();
        const ambient = new THREE.AmbientLight(0xffffff, 0.15);
        const backLight = new THREE.DirectionalLight(0xffffff, 0.3);
        const keyLight = new THREE.DirectionalLight(
            new THREE.Color('#EEEEEE'),
            0.3
        );
        const fillLight = new THREE.DirectionalLight(
            new THREE.Color('#EEEEEE'),
            0.2
        );

        keyLight.position.set(-100, 0, 100);
        fillLight.position.set(100, 0, 100);
        backLight.position.set(100, 0, -100).normalize();

        const hemiLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 0.6);
        hemiLight.groundColor.setHSL(0.095, 1, 0.95);
        hemiLight.position.set(0, 100, 0);
        scene.add(hemiLight);

        scene.add(ambient);
        scene.add(keyLight);
        scene.add(fillLight);
        scene.add(backLight);
        scene.lights = { keyLight, fillLight, backLight, ambient };


        var mtlLoader = new THREE.MTLLoader();
        mtlLoader.setTexturePath("assets/");
        mtlLoader.setPath("assets/");
        mtlLoader.load('Door 2 N230518.mtl', function (materials) {

            materials.preload();

            var objLoader = new THREE.OBJLoader();
            objLoader.setMaterials(materials);
            objLoader.setPath("assets/");
            objLoader.load('Door 2 N230518.obj', function (object) {
                
                scene.add(object);

            });

        });

        //render 3d view to canvas
        renderer = new THREE.WebGLRenderer();
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setClearColor(new THREE.Color("hsl(0, 0%, 10%)"));

        container.appendChild(renderer.domElement);

        /* Controls */
        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.25;
        controls.enableZoom = true;

        /* Events */
        window.addEventListener('resize', onWindowResize, false);

    }

    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    }
    

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        render();
    }

    function render() {
        renderer.render(scene, camera);
    }

    function zoomIn() {
        zoomscale += 0.1;
        camera.zoom = zoomscale;
        camera.updateProjectionMatrix();        

    }
    function zoomOut() {
        if (zoomscale >= 0.4) {
            zoomscale -= 0.1;
            camera.zoom = zoomscale;
            camera.updateProjectionMatrix();      
        }
    }

</script>

</body>
</html>