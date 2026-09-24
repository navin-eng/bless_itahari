<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #1a1a2e;
            --text-color: #ffffff;
            --accent-color: #e94560;
            --cat-color: #fca311;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-color);
            color: var(--text-color);
            font-family: 'Nunito', sans-serif;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            user-select: none; /* prevent text selection while clicking fast */
        }

        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite alternate;
        }

        @keyframes twinkle {
            from { opacity: 0.2; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .content {
            z-index: 10;
            position: absolute;
            top: 10%;
            pointer-events: none;
        }

        h1 {
            font-size: 5rem;
            margin: 0;
            line-height: 1;
            background: linear-gradient(45deg, #fca311, #e94560);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        p {
            font-size: 1.2rem;
            margin: 10px 0 20px;
            color: #aeb4c0;
        }

        .btn-home {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(233, 69, 96, 0.4);
            border: none;
            cursor: pointer;
            pointer-events: auto;
            position: relative;
            z-index: 100;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(233, 69, 96, 0.6);
            background: #ff5773;
        }

        /* --- Score & Game Area --- */
        .score-display {
            position: absolute;
            top: 20px;
            right: 20px;
            font-weight: 900;
            font-size: 2rem;
            color: #fca311;
            z-index: 10;
        }

        .instruction {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            z-index: 10;
            pointer-events: none;
        }

        /* --- The Sleeping Cat --- */
        .cat-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            pointer-events: none;
        }
        
        .cat {
            position: relative;
            width: 120px;
            height: 80px;
            background-color: var(--cat-color);
            border-radius: 60px 60px 20px 20px;
            box-shadow: inset -10px -10px 20px rgba(0,0,0,0.2);
            animation: breathe 3s ease-in-out infinite;
        }

        @keyframes breathe {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.05); }
        }

        /* Ears */
        .cat::before, .cat::after {
            content: '';
            position: absolute;
            top: -20px;
            width: 0; 
            height: 0; 
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 35px solid var(--cat-color);
        }
        .cat::before { left: -5px; transform: rotate(-20deg); }
        .cat::after { right: -5px; transform: rotate(20deg); }

        /* Zzz animation */
        .zzz {
            position: absolute;
            top: -30px;
            right: -20px;
            font-size: 2rem;
            font-weight: 900;
            color: white;
            opacity: 0;
            animation: sleep 3s linear infinite;
        }

        @keyframes sleep {
            0% { transform: translate(0, 0) scale(0.5); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translate(40px, -60px) scale(1.5); opacity: 0; }
        }

        /* --- Noise Makers (Targets) --- */
        .game-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
        }

        .noise-maker {
            position: absolute;
            width: 60px;
            height: 60px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            cursor: crosshair;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            transform: scale(0);
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes popIn {
            to { transform: scale(1); }
        }

        /* The shrinking timer ring around the target */
        .noise-maker::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 4px solid var(--accent-color);
            border-radius: 50%;
            box-sizing: border-box;
            pointer-events: none;
            /* Animation applied via JS */
        }

        .noise-maker.active {
            animation: shake 0.5s infinite;
        }

        @keyframes shake {
            0% { transform: translate(1px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(3px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            60% { transform: translate(-3px, 1px) rotate(0deg); }
            70% { transform: translate(3px, 1px) rotate(-1deg); }
            80% { transform: translate(-1px, -1px) rotate(1deg); }
            90% { transform: translate(1px, 2px) rotate(0deg); }
            100% { transform: translate(1px, -2px) rotate(-1deg); }
        }

        .lose-flash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #e94560;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
    </style>
</head>
<body>

    <div class="stars" id="stars"></div>
    <div class="lose-flash" id="loseFlash"></div>

    <div class="content">
        <h1>404</h1>
        <p>Shh! Don't wake the lazy cat.</p>
        <a href="{{ route('home') }}" class="btn-home">Take Me Home</a>
    </div>

    <div class="score-display">Score: <span id="score">0</span></div>
    
    <div class="cat-container">
        <div class="cat">
            <div class="zzz">z</div>
        </div>
    </div>
    
    <div class="instruction">Tap the noisy objects to silence them before their ring shrinks!</div>

    <div class="game-area" id="gameArea"></div>

    <script>
        // --- Web Audio API Sound Effects ---
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        
        function playSound(type) {
            // Resume context if suspended (browser autoplay policies)
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            const now = audioCtx.currentTime;
            
            if (type === 'pop') {
                // Happy pop sound for clicking targets
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(600, now);
                oscillator.frequency.exponentialRampToValueAtTime(800, now + 0.1);
                
                gainNode.gain.setValueAtTime(0.5, now);
                gainNode.gain.exponentialRampToValueAtTime(0.01, now + 0.1);
                
                oscillator.start(now);
                oscillator.stop(now + 0.1);
                
            } else if (type === 'lose') {
                // Harsh angry buzzer sound for Game Over
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, now);
                oscillator.frequency.linearRampToValueAtTime(100, now + 0.5);
                
                gainNode.gain.setValueAtTime(0.8, now);
                gainNode.gain.linearRampToValueAtTime(0.01, now + 0.5);
                
                // Add a second oscillator for dissonance
                const osc2 = audioCtx.createOscillator();
                osc2.type = 'square';
                osc2.frequency.setValueAtTime(140, now);
                osc2.frequency.linearRampToValueAtTime(90, now + 0.5);
                osc2.connect(gainNode);
                osc2.start(now);
                osc2.stop(now + 0.5);
                
                oscillator.start(now);
                oscillator.stop(now + 0.5);
            } else if (type === 'spawn') {
                // Very subtle blip when an object spawns
                oscillator.type = 'triangle';
                oscillator.frequency.setValueAtTime(300, now);
                
                gainNode.gain.setValueAtTime(0.1, now);
                gainNode.gain.exponentialRampToValueAtTime(0.01, now + 0.05);
                
                oscillator.start(now);
                oscillator.stop(now + 0.05);
            }
        }

        // Starry background generator
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 50; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.width = Math.random() * 3 + 'px';
            star.style.height = star.style.width;
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.setProperty('--duration', (Math.random() * 2 + 1) + 's');
            starsContainer.appendChild(star);
        }

        // Game Logic
        const gameArea = document.getElementById('gameArea');
        const scoreElement = document.getElementById('score');
        
        const emojis = ['⏰', '🐕', '🔔', '🎺', '📱', '🐔'];
        
        let score = 0;
        let isGameOver = false;
        
        // Difficulty parameters
        let spawnIntervalTime = 2000; // Time between spawns (ms)
        let shrinkTime = 4000; // How long user has to click before lose (ms)
        
        let spawnTimer;

        // Start Game
        function startGame() {
            spawnTimer = setTimeout(spawnNoiseMaker, spawnIntervalTime);
        }

        function spawnNoiseMaker() {
            if (isGameOver) return;
            
            // Play spawn sound
            playSound('spawn');

            // Create element
            const noise = document.createElement('div');
            noise.className = 'noise-maker';
            
            // Random Emoji
            noise.innerText = emojis[Math.floor(Math.random() * emojis.length)];

            // Random Position (avoiding the exact center where the cat is)
            let x, y;
            do {
                x = Math.random() * (window.innerWidth - 100) + 20;
                y = Math.random() * (window.innerHeight - 100) + 20;
                
                // Center coordinates approx (vw/2, vh/2)
                let cx = window.innerWidth / 2;
                let cy = window.innerHeight / 2;
                let dist = Math.sqrt(Math.pow(x - cx, 2) + Math.pow(y - cy, 2));
            } while(Math.sqrt(Math.pow(x - (window.innerWidth/2), 2) + Math.pow(y - (window.innerHeight/2), 2)) < 150); // Keep at least 150px away from center

            noise.style.left = x + 'px';
            noise.style.top = y + 'px';

            // Animation for the shrinking ring
            noise.style.setProperty('--shrink-duration', shrinkTime + 'ms');
            
            // Add custom style for the pseudo element via inline style trick
            // Actually, we'll handle the visual shrinking via JS scale, or CSS animation
            const style = document.createElement('style');
            const animId = 'shrinkAnim_' + Math.random().toString(36).substr(2, 9);
            style.innerHTML = `
                @keyframes ${animId} {
                    0% { transform: scale(2); opacity: 0; }
                    10% { transform: scale(1.5); opacity: 1; }
                    100% { transform: scale(0.9); opacity: 1; border-color: red; }
                }
                .noise-${animId}::before {
                    animation: ${animId} ${shrinkTime}ms linear forwards;
                }
            `;
            document.head.appendChild(style);
            noise.classList.add(`noise-${animId}`);

            // Make it shake when time is almost up
            setTimeout(() => {
                if(!isGameOver && document.body.contains(noise)) {
                    noise.classList.add('active');
                }
            }, shrinkTime * 0.7);

            // Click to silence
            noise.onmousedown = (e) => {
                if (isGameOver) return;
                // Prevent multi-touch bugs
                if (e.type === 'mousedown') {
                    e.preventDefault();
                }
                
                silence(noise, style);
            };
            noise.ontouchstart = (e) => {
                if (isGameOver) return;
                e.preventDefault();
                silence(noise, style);
            };

            // Set Death Timer
            noise.deathTimer = setTimeout(() => {
                if(document.body.contains(noise)) {
                    gameOver();
                }
            }, shrinkTime);

            gameArea.appendChild(noise);

            // Schedule next spawn with increasing difficulty
            spawnIntervalTime = Math.max(400, spawnIntervalTime * 0.95); // Faster spawns (min 400ms)
            shrinkTime = Math.max(1000, shrinkTime * 0.98); // Less time to click (min 1s)

            spawnTimer = setTimeout(spawnNoiseMaker, spawnIntervalTime);
        }

        function silence(element, styleElement) {
            clearTimeout(element.deathTimer);
            element.remove();
            if(styleElement) styleElement.remove();
            
            playSound('pop');
            
            score += 10;
            scoreElement.innerText = score;
            
            // Visual pop effect on click
            const pop = document.createElement('div');
            pop.innerText = '💥';
            pop.style.position = 'absolute';
            pop.style.left = element.style.left;
            pop.style.top = element.style.top;
            pop.style.fontSize = '30px';
            pop.style.transition = 'all 0.3s';
            pop.style.transform = 'scale(1)';
            pop.style.opacity = '1';
            gameArea.appendChild(pop);
            
            setTimeout(() => {
                pop.style.transform = 'scale(2)';
                pop.style.opacity = '0';
            }, 10);
            
            setTimeout(() => {
                pop.remove();
            }, 300);
        }

        function gameOver() {
            isGameOver = true;
            clearTimeout(spawnTimer);
            
            playSound('lose');
            
            // Flash screen red
            document.getElementById('loseFlash').style.opacity = '1';
            
            // Redirect to home after 1 second
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        }

        // Delay start slightly to let user read
        setTimeout(startGame, 1500);

    </script>
</body>
</html>
