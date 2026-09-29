<?php
/** @var array $work */

$photos = [
    'assets/gambargeng.jpeg',
    'assets/gambarnetwork.jpeg',
    'assets/design.jpeg',
    'assets/team.jpeg',
    'assets/finish.jpeg',
    'assets/win.jpeg',
];

// Leave as '' to keep the orange knot.
$logo = 'assets/umpsalogoo.png';

// Set to true to show ONLY your photos and hide the old poster panels.
$photosOnly = true;

$photosOnly = $photosOnly ?? false;
if ($photosOnly) {
    $work = [];
}

$panelItems = array_map(static function (array $item): array {
    return [
        'title'  => $item['title'] ?? '',
        'colors' => $item['colors'] ?? ['#0e1530', '#ff5a1f', '#fff2e8'],
        'image'  => $item['image'] ?? null,
    ];
}, $work);

foreach ($photos as $photo) {
    $panelItems[] = [
        'title'  => is_array($photo) ? ($photo['title'] ?? '') : '',
        'colors' => ['#0e1530', '#ff5a1f', '#fff2e8'],
        'image'  => is_array($photo) ? ($photo['image'] ?? null) : $photo,
    ];
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
<script>
window.__WORK__ = <?= json_encode($panelItems, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
window.__LOGO__ = <?= json_encode($logo ?? '', JSON_UNESCAPED_SLASHES) ?>;

window.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var WORK   = window.__WORK__ || [];
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  var FONT_FAMILY = 'Unbounded, "Arial Black", Arial, sans-serif';
  var PANEL_W = 2.6, PANEL_H = 3.25;
  var TEX_W = 512, TEX_H = 640;

  document.querySelectorAll('.tilt').forEach(function (el) {
    el.addEventListener('pointermove', function (e) {
      var r = el.getBoundingClientRect();
      var x = (e.clientX - r.left) / r.width  - 0.5;
      var y = (e.clientY - r.top)  / r.height - 0.5;
      el.style.transform = 'rotateY(' + (x * 8) + 'deg) rotateX(' + (-y * 8) + 'deg)';
    });
    el.addEventListener('pointerleave', function () {
      el.style.transform = '';
    });
  });

  var copyBtn = document.getElementById('copy');
  var mailEl  = document.getElementById('mail');
  if (copyBtn && mailEl) {
    copyBtn.addEventListener('click', function () {
      var text = mailEl.textContent.trim();
      function flash(label) {
        copyBtn.textContent = label;
        setTimeout(function () { copyBtn.textContent = 'Copy email'; }, 1800);
      }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(
          function () { flash('Copied'); },
          function () { flash('Select the address'); }
        );
      } else {
        flash('Select the address');
      }
    });
  }

  var bar = document.getElementById('bar');
  function progress() {
    var h = document.documentElement.scrollHeight - window.innerHeight;
    return h > 0 ? Math.min(1, Math.max(0, window.scrollY / h)) : 0;
  }

  if (typeof THREE === 'undefined') return;
  var canvas = document.getElementById('gl');
  if (!canvas) return;

  try {
    var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    var scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0e1530);
    scene.fog        = new THREE.Fog(0x0e1530, 8, 46);

    var cam = new THREE.PerspectiveCamera(60, 1, 0.1, 80);

    scene.add(new THREE.AmbientLight(0xffffff, 0.6));
    var d1 = new THREE.DirectionalLight(0xffffff, 1.1);
    d1.position.set(4, 6, 8);
    scene.add(d1);

    var pl = new THREE.PointLight(0xff5a1f, 2, 30);
    pl.position.set(0, 2, -20);
    scene.add(pl);

    var hero = new THREE.Group();
    hero.position.set(2.6, 0, 0);
    scene.add(hero);

    var knot = new THREE.Mesh(
      new THREE.TorusKnotGeometry(1.1, 0.34, 200, 24),
      new THREE.MeshStandardMaterial({ color: 0xff5a1f, metalness: 0.4, roughness: 0.25 })
    );
    var cage = new THREE.Mesh(
      new THREE.IcosahedronGeometry(2.2, 1),
      new THREE.MeshBasicMaterial({
        color: 0xfff2e8, wireframe: true, transparent: true, opacity: 0.3
      })
    );
    hero.add(knot, cage);

    var emblem = knot;

    function keyOutWhite(img) {
      var max = 1024;
      var k = Math.min(1, max / Math.max(img.naturalWidth, img.naturalHeight));
      var w = Math.round(img.naturalWidth * k), h = Math.round(img.naturalHeight * k);
      var cn = document.createElement('canvas');
      cn.width = w; cn.height = h;
      var c = cn.getContext('2d');
      c.drawImage(img, 0, 0, w, h);
      var data = c.getImageData(0, 0, w, h);
      var px = data.data;
      for (var i = 0; i < px.length; i += 4) {
        var m = Math.min(px[i], px[i + 1], px[i + 2]);
        var al = (245 - m) / 55;
        al = al < 0 ? 0 : al > 1 ? 1 : al;
        px[i + 3] = Math.round(px[i + 3] * al);
      }
      c.putImageData(data, 0, 0);
      var tex = new THREE.CanvasTexture(cn);
      tex.anisotropy = renderer.capabilities.getMaxAnisotropy();
      return tex;
    }

    function makeShadowTexture(src, pad) {
      var w = src.width, h = src.height;
      var cn = document.createElement('canvas');
      cn.width = w + pad * 2;
      cn.height = h + pad * 2;
      var c = cn.getContext('2d');
      var FAR = 6000;

      function layer(color, blur, dx, dy) {
        c.save();
        c.shadowColor = color;
        c.shadowBlur = blur;
        c.shadowOffsetX = FAR + dx;
        c.shadowOffsetY = dy;
        c.drawImage(src, pad - FAR, pad);
        c.restore();
      }

      layer('rgba(8,4,30,0.5)', 34, 16, 26);
      layer('rgba(8,4,30,0.55)', 7, 4, 7);

      var tex = new THREE.CanvasTexture(cn);
      tex.anisotropy = renderer.capabilities.getMaxAnisotropy();
      return tex;
    }

    function roundedRect(w, h, r) {
      var x = -w / 2, y = -h / 2;
      var sh = new THREE.Shape();
      sh.moveTo(x + r, y);
      sh.lineTo(x + w - r, y);
      sh.quadraticCurveTo(x + w, y, x + w, y + r);
      sh.lineTo(x + w, y + h - r);
      sh.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
      sh.lineTo(x + r, y + h);
      sh.quadraticCurveTo(x, y + h, x, y + h - r);
      sh.lineTo(x, y + r);
      sh.quadraticCurveTo(x, y, x + r, y);
      return sh;
    }

    function makeGlowTexture() {
      var cn = document.createElement('canvas');
      cn.width = cn.height = 256;
      var c = cn.getContext('2d');
      var g = c.createRadialGradient(128, 128, 0, 128, 128, 128);
      g.addColorStop(0, 'rgba(255,110,50,0.75)');
      g.addColorStop(0.45, 'rgba(255,90,31,0.22)');
      g.addColorStop(1, 'rgba(255,90,31,0)');
      c.fillStyle = g;
      c.fillRect(0, 0, 256, 256);
      return new THREE.CanvasTexture(cn);
    }

    function makeGlareTexture() {
      var cn = document.createElement('canvas');
      cn.width = 256; cn.height = 256;
      var c = cn.getContext('2d');
      c.translate(128, 128);
      c.rotate(-0.35);
      var g = c.createLinearGradient(-40, 0, 40, 0);
      g.addColorStop(0, 'rgba(255,255,255,0)');
      g.addColorStop(0.5, 'rgba(255,255,255,0.75)');
      g.addColorStop(1, 'rgba(255,255,255,0)');
      c.fillStyle = g;
      c.fillRect(-40, -260, 80, 520);
      var tex = new THREE.CanvasTexture(cn);
      return tex;
    }

    function buildBadge(img) {
      var badge = new THREE.Group();

      var box = 2.5;
      var aspect = img.naturalWidth / img.naturalHeight;
      var pw = aspect >= 1 ? box : box * aspect;
      var ph = aspect >= 1 ? box / aspect : box;
      var slabW = pw + 0.3, slabH = ph + 0.3;

      var depth = 0.14, bevel = 0.05;
      var slabGeo = new THREE.ExtrudeGeometry(roundedRect(slabW, slabH, 0.2), {
        depth: depth,
        bevelEnabled: true,
        bevelThickness: bevel,
        bevelSize: bevel,
        bevelSegments: 5,
        curveSegments: 14
      });
      slabGeo.center();

      var faceMat = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.28, metalness: 0.05 });
      var edgeMat = new THREE.MeshStandardMaterial({ color: 0xff5a1f, emissive: 0x3a1200, metalness: 0.45, roughness: 0.38 });
      var slab = new THREE.Mesh(slabGeo, [faceMat, edgeMat]);
      badge.add(slab);

      var front = (depth + bevel * 2) / 2;
      var logoTex = keyOutWhite(img);

      var PAD = 64;
      var srcCanvas = logoTex.image;
      var shadowScale = (srcCanvas.width + PAD * 2) / srcCanvas.width;
      var shadow = new THREE.Mesh(
        new THREE.PlaneGeometry(pw * shadowScale, ph * (srcCanvas.height + PAD * 2) / srcCanvas.height),
        new THREE.MeshBasicMaterial({
          map: makeShadowTexture(srcCanvas, PAD), transparent: true, depthWrite: false
        })
      );
      shadow.position.z = front + 0.015;

      var logo = new THREE.Mesh(
        new THREE.PlaneGeometry(pw, ph),
        new THREE.MeshBasicMaterial({ map: logoTex, transparent: true, alphaTest: 0.02 })
      );
      logo.position.z = front + 0.045;

      var glareTex = makeGlareTexture();
      glareTex.offset.x = -0.9;
      var gw = slabW - 0.24, gh = slabH - 0.24;
      var glareGeo = new THREE.ShapeGeometry(roundedRect(gw, gh, 0.14), 14);
      var gp = glareGeo.attributes.position, gu = glareGeo.attributes.uv;
      for (var gi = 0; gi < gp.count; gi++) {
        gu.setXY(gi, gp.getX(gi) / gw + 0.5, gp.getY(gi) / gh + 0.5);
      }
      var glare = new THREE.Mesh(
        glareGeo,
        new THREE.MeshBasicMaterial({
          map: glareTex, transparent: true, blending: THREE.AdditiveBlending, depthWrite: false
        })
      );
      glare.position.z = front + 0.05;

      badge.add(shadow, logo, glare);
      badge.userData.glareTex = glareTex;

      var glow = new THREE.Sprite(new THREE.SpriteMaterial({
        map: makeGlowTexture(), blending: THREE.AdditiveBlending, depthWrite: false, transparent: true
      }));
      glow.scale.set(8, 8, 1);
      glow.position.z = -0.8;
      hero.add(glow);

      var warm = new THREE.PointLight(0xff7a3a, 1.6, 14);
      warm.position.set(-2.5, 2, 4.5);
      var cool = new THREE.PointLight(0xbfd4ff, 0.45, 14);
      cool.position.set(6, -2, 5);
      hero.add(warm, cool);

      return badge;
    }

    if (window.__LOGO__) {
      var logoImg = new Image();
      logoImg.crossOrigin = 'anonymous';
      logoImg.onload = function () {
        var badge = buildBadge(logoImg);
        hero.remove(knot);
        hero.add(badge);
        emblem = badge;
      };
      logoImg.src = window.__LOGO__;
    }

    var panels = [];

    function newCanvas() {
      var cn = document.createElement('canvas');
      cn.width = TEX_W;
      cn.height = TEX_H;
      return cn;
    }

    function makePosterTexture(colors, name) {
      var cn = newCanvas();
      var x = cn.getContext('2d');

      x.fillStyle = colors[0];
      x.fillRect(0, 0, TEX_W, TEX_H);

      x.fillStyle = colors[1];
      x.beginPath();
      x.arc(170, 210, 110, 0, Math.PI * 2);
      x.fill();

      x.save();
      x.translate(360, 400);
      x.rotate(0.3);
      x.fillStyle = colors[2];
      x.fillRect(-120, -120, 240, 240);
      x.restore();

      x.fillStyle = colors[2];
      x.fillRect(60, 420, 170, 16);

      x.fillStyle = '#101a3a';
      x.font = '800 54px ' + FONT_FAMILY;
      x.fillText(name, 40, 584);

      return new THREE.CanvasTexture(cn);
    }

    function makePhotoTexture(img, caption) {
      var cn = newCanvas();
      var x = cn.getContext('2d');
      var border = 14;
      var capH = caption ? 78 : 0;

      x.fillStyle = '#fff2e8';
      x.fillRect(0, 0, TEX_W, TEX_H);

      var bx = border, by = border;
      var bw = TEX_W - border * 2;
      var bh = TEX_H - border * 2 - capH;

      var scale = Math.max(bw / img.naturalWidth, bh / img.naturalHeight);
      var sw = bw / scale, sh = bh / scale;
      var sx = (img.naturalWidth - sw) / 2;
      var sy = (img.naturalHeight - sh) / 2;

      x.drawImage(img, sx, sy, sw, sh, bx, by, bw, bh);

      if (caption) {
        x.fillStyle = '#101a3a';
        x.font = '800 30px ' + FONT_FAMILY;
        x.textBaseline = 'middle';
        x.fillText(caption, border + 6, TEX_H - capH / 2 - border / 2 + 4, bw - 12);
      }

      var tex = new THREE.CanvasTexture(cn);
      tex.anisotropy = renderer.capabilities.getMaxAnisotropy();
      return tex;
    }

    function paint(mesh) {
      var d = mesh.userData;
      var next = d.img
        ? makePhotoTexture(d.img, d.title)
        : makePosterTexture(d.colors, d.title);
      if (mesh.material.map) mesh.material.map.dispose();
      mesh.material.map = next;
      mesh.material.needsUpdate = true;
    }

    var step = Math.min(3.4, 26 / Math.max(1, WORK.length - 1));

    WORK.forEach(function (item, i) {
      var mesh = new THREE.Mesh(
        new THREE.PlaneGeometry(PANEL_W, PANEL_H),
        new THREE.MeshBasicMaterial({ side: THREE.DoubleSide })
      );
      var a = i * 1.7;
      mesh.position.set(Math.cos(a) * 3.1, Math.sin(a) * 1.3, -11 - i * step);
      mesh.userData = {
        baseY: mesh.position.y,
        index: i,
        title: item.title || '',
        colors: item.colors,
        img: null
      };
      paint(mesh);
      scene.add(mesh);
      panels.push(mesh);

      if (item.image) {
        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function () {
          mesh.userData.img = img;
          paint(mesh);
        };
        img.src = item.image;
      }
    });

    if (document.fonts && document.fonts.load) {
      document.fonts.load('800 54px Unbounded').then(function () {
        panels.forEach(paint);
      }).catch(function () {});
    }

    var N = 16, gap = 1.2;
    var cubes = new THREE.InstancedMesh(
      new THREE.BoxGeometry(0.9, 0.9, 0.9),
      new THREE.MeshStandardMaterial({ color: 0xff8a5c, metalness: 0.2, roughness: 0.35 }),
      N * N
    );
    cubes.position.set(0, -3.4, -40);
    scene.add(cubes);
    var dummy = new THREE.Object3D();

    var rings = [];
    for (var i = 0; i < 9; i++) {
      var ring = new THREE.Mesh(
        new THREE.TorusGeometry(2.6 + i * 0.05, 0.05, 10, 80),
        new THREE.MeshBasicMaterial({ color: i % 2 ? 0xff5a1f : 0xfff2e8 })
      );
      ring.position.z = -49 - i * 2.6;
      scene.add(ring);
      rings.push(ring);
    }

    var n = 700;
    var positions = new Float32Array(n * 3);
    for (var j = 0; j < n; j++) {
      positions[j * 3]     = (Math.random() - 0.5) * 26;
      positions[j * 3 + 1] = (Math.random() - 0.5) * 14;
      positions[j * 3 + 2] = 8 - Math.random() * 72;
    }
    var particleGeo = new THREE.BufferGeometry();
    particleGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    scene.add(new THREE.Points(
      particleGeo,
      new THREE.PointsMaterial({ color: 0xfff2e8, size: 0.05 })
    ));

    function resize() {
      renderer.setSize(window.innerWidth, window.innerHeight, false);
      cam.aspect = window.innerWidth / window.innerHeight;
      cam.updateProjectionMatrix();
    }
    resize();
    window.addEventListener('resize', resize);

    var mx = 0, my = 0, sm = 0, cx = 0, cy = 0;
    var clock = new THREE.Clock();

    window.addEventListener('pointermove', function (e) {
      mx = (e.clientX / window.innerWidth)  * 2 - 1;
      my = (e.clientY / window.innerHeight) * 2 - 1;
    });

    function frame() {
      requestAnimationFrame(frame);

      var t = clock.getElapsedTime();
      var p = progress();
      if (bar) bar.style.transform = 'scaleX(' + p + ')';

      sm += (p - sm) * 0.06;
      cx += (mx - cx) * 0.05;
      cy += (my - cy) * 0.05;

      var z = 7 - sm * 59;
      cam.position.set(
        Math.sin(sm * 9) * 0.9 + cx * 1.1,
        -cy * 0.7 + Math.sin(sm * 6) * 0.3,
        z
      );
      cam.lookAt(cx * 1.5, -cy * 0.9, z - 8);
      cam.rotation.z = Math.sin(sm * 9) * 0.03;

      if (!reduce) {
        if (emblem === knot) {
          knot.rotation.x = t * 0.4;
          knot.rotation.y = t * 0.55;
        }
        cage.rotation.y = -t * 0.2;
      }

      if (emblem !== knot) {
        var swayY = (reduce ? 0 : Math.sin(t * 0.6) * 0.45) + cx * 0.35;
        var swayX = (reduce ? 0 : Math.sin(t * 0.8) * 0.05) + cy * 0.22;
        emblem.rotation.y += (swayY - emblem.rotation.y) * 0.08;
        emblem.rotation.x += (swayX - emblem.rotation.x) * 0.08;
        if (!reduce) {
          emblem.userData.glareTex.offset.x = ((t * 0.22) % 2.2) - 1.1;
        }
      }
      hero.position.y = Math.sin(t * 0.8) * 0.15;

      panels.forEach(function (mesh) {
        mesh.position.y = mesh.userData.baseY
          + (reduce ? 0 : Math.sin(t * 0.9 + mesh.userData.index) * 0.18);
        mesh.rotation.y = -mesh.position.x * 0.18
          + Math.sin(t * 0.5 + mesh.userData.index) * 0.05;
      });

      for (var a = 0; a < N; a++) {
        for (var b = 0; b < N; b++) {
          var h = reduce ? 0 : Math.sin(a * 0.5 + t) * Math.cos(b * 0.5 + t * 0.8) * 1.1;
          dummy.position.set((a - N / 2) * gap, h, -b * gap + 6);
          dummy.scale.set(1, 1 + Math.abs(h) * 0.6, 1);
          dummy.updateMatrix();
          cubes.setMatrixAt(a * N + b, dummy.matrix);
        }
      }
      cubes.instanceMatrix.needsUpdate = true;

      rings.forEach(function (ring, i) {
        ring.rotation.z = reduce ? 0 : t * 0.3 * (i % 2 ? 1 : -1);
      });

      renderer.render(scene, cam);
    }
    frame();

  } catch (err) {
    if (canvas) canvas.style.display = 'none';
  }
});
</script>