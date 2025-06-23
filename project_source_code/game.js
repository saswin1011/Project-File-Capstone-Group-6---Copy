// === INITIAL SETUP ===
let gameEnded = false;
let lives = window.wrongAnswerLimit || 3;
let currentQuestionIndex = 0;
let showQuestion = false;
let isPaused = false;
let nearbyDoorIndex = null;
let knightFrame = 0;
let knightTick = 0;
const frameSpeed = 17; // lower = faster animation
const wizardAnimSpeed = 25; // higher = slower animation
const spriteWidth = 128;
const spriteHeight = 128;



const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const savedAvatar = localStorage.getItem("knightAvatar") || "knight1.png";
const avatarFolder = savedAvatar.replace('.png', ''); // "knight1" → folder name
const knightIdleImg = new Image();
const knightRunImg = new Image();
knightIdleImg.src = `assets/${avatarFolder}/idle.png`;
knightRunImg.src = `assets/${avatarFolder}/run.png`;
const doorImg = new Image(); doorImg.src = 'assets/door.png';
const dragonImg = new Image(); dragonImg.src = 'assets/dragon.png';
const fireImg = new Image(); fireImg.src = 'assets/fire.png';
const wizardImg = new Image();
wizardImg.src = 'assets/wizard_idle.png';

const wizardFrameCount = 10;
const wizardFrameWidth = wizardImg.width / wizardFrameCount; // will compute dynamically once loaded
const wizardFrameHeight = 64; // you can adjust if different
let wizardFrame = 0;

const bgImg = new Image(); bgImg.src = 'assets/arena_bg.png';
const heartImg = new Image(); heartImg.src = 'assets/heart.png';
const bookshelfImg = new Image();
bookshelfImg.src = 'assets/bookshelf.png';
// === SOUND SETUP ===
const sound = {
  footstep: new Audio("assets/footstep.mp3"),
  doorOpen: new Audio("assets/door_open.mp3"),
  correct: new Audio("assets/correct.mp3"),
  fire: new Audio("assets/fire.mp3"),
  startQuestion: new Audio("assets/start_question.mp3"),
  win: new Audio("assets/win_fanfare.mp3"),
  lose: new Audio("assets/lose.mp3")
};

// Optional: Set consistent lower volume
for (const key in sound) {
  sound[key].volume = 0.4;
}



const groundY = canvas.height - 250; // fixed ground level for knight
let fireProgress = 0;
const maxFireProgress = 30;
let fireTarget = { x: 0, y: 0 };

let knight = {
  x: 100,
  y: groundY,
  width: 128,
  height: 128,
  speed: 4,
  direction: "right", // <-- add this
  moving: { left: false, right: false, up: false, down: false }
};



const doorY = groundY - 100;
const doors = [
  { x: canvas.width * 0.15, y: doorY },
  { x: canvas.width * 0.35, y: doorY },
  { x: canvas.width * 0.55, y: doorY },
  { x: canvas.width * 0.75, y: doorY }
];

const bookshelf = {
  x: canvas.width / 2 - 30,
  y: groundY - -70,
  width: 60,
  height: 60
};


function detectNearbyDoor() {
  nearbyDoorIndex = null;
  doors.forEach((door, index) => {
    const dx = knight.x + knight.width / 2 - (door.x + 110); // 220 / 2
    const dy = knight.y + knight.height / 2 - (door.y + 130); // 260 / 2
    const dist = Math.sqrt(dx * dx + dy * dy);
    if (dist < 120) { // larger threshold to match bigger door
      nearbyDoorIndex = index;
    }
  });
}


function isNearWizard() {
  const wizardX = canvas.width / 2 - 60;
  const wizardY = canvas.height * 0.25;
  const dx = knight.x + knight.width / 2 - (wizardX + 40);
  const dy = knight.y + knight.height / 2 - (wizardY + 40);
  return Math.sqrt(dx * dx + dy * dy) < 100;
}

function isNearBookshelf() {
  const dx = knight.x + knight.width / 2 - (bookshelf.x + bookshelf.width / 2);
  const dy = knight.y + knight.height / 2 - (bookshelf.y + bookshelf.height / 2);
  return Math.sqrt(dx * dx + dy * dy) < 80;
}

function isNearDoor(knight, door) {
  const dx = knight.x + knight.width / 2 - (door.x + 110); // 220 / 2
  const dy = knight.y + knight.height / 2 - (door.y + 130); // 260 / 2
  const dist = Math.sqrt(dx * dx + dy * dy);
  return dist < 120; // increased range
}


// === KEY EVENTS ===
document.addEventListener("keydown", (e) => {
  const key = e.key.replace("Arrow", "").toLowerCase();
  knight.moving[key] = true;

  if (key === "left") knight.direction = "left";
  else if (key === "right") knight.direction = "right";

  if ((e.key === "q" || e.key === "Q") && isNearBookshelf()) {
    showQuestion = true;
    sound.startQuestion.play();
  }
  

  if ((e.key === "e" || e.key === "E") && showQuestion && nearbyDoorIndex !== null) {
    sound.doorOpen.play();
    checkAnswer(nearbyDoorIndex);
    showQuestion = false;
  }
  

  if (e.key === "Escape") {
    isPaused = !isPaused;
    document.getElementById("pauseOverlay").style.display = isPaused ? "flex" : "none";
  }
});



document.addEventListener("keyup", (e) => {
  knight.moving[e.key.replace("Arrow", "").toLowerCase()] = false;
});

// === MAIN LOOP ===
function gameLoop() {
  if (gameEnded || isPaused) return;

  // Horizontal movement only
  if (knight.moving.left && knight.x > 0) {
    knight.x -= knight.speed;
    if (knightTick % 20 === 0) sound.footstep.play();
  }
  if (knight.moving.right && knight.x + knight.width < canvas.width) {
    knight.x += knight.speed;
    if (knightTick % 20 === 0) sound.footstep.play();
  }
  
  
  // Always stick to ground
  knight.y = groundY;

  ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
  ctx.fillStyle = "rgba(0, 0, 0, 0.5)";
  ctx.fillRect(0, 0, canvas.width, 50);
  for (let i = 0; i < lives; i++) ctx.drawImage(heartImg, 20 + i * 40, 10, 30, 30);

  doors.forEach((door, i) => {
    if (isNearDoor(knight, door)) {
      ctx.fillStyle = 'rgba(255, 215, 0, 0.4)';
      ctx.fillRect(door.x - 10, door.y - 10, 240, 280);
    }
    ctx.drawImage(doorImg, door.x, door.y, 220, 260);
  });

// 🪶 Floating animation values
const wizardFloat = Math.sin(Date.now() / 500) * 5;
const dragonFloat = Math.sin(Date.now() / 600) * 4;

// 🪑 Bookshelf
ctx.drawImage(bookshelfImg, bookshelf.x, bookshelf.y, bookshelf.width, bookshelf.height);

// 🐉 DRAGON ANIMATION (3 frames in 1 row)
const dragonFrameCount = 3;
const dragonFrameWidth = 432 / dragonFrameCount; // = 144
const dragonFrameHeight = 94;

const dragonFrame = Math.floor(knightTick / 30) % dragonFrameCount;

const dragonDisplayWidth = 300;
const dragonDisplayHeight = 260;


ctx.drawImage(
  dragonImg,
  dragonFrame * dragonFrameWidth, 0,           // source x, y
  dragonFrameWidth, dragonFrameHeight,         // source width, height
  canvas.width / 2 - dragonDisplayWidth / 2,   // destination x (centered)
  80 + Math.sin(Date.now() / 600) * 4,         // destination y (floating)
  dragonDisplayWidth, dragonDisplayHeight      // destination width, height
);



// 🛡 Knight
const isMoving = knight.moving.left || knight.moving.right;
const sprite = isMoving ? knightRunImg : knightIdleImg;

ctx.save();

if (knight.direction === "left") {
  ctx.scale(-1, 1); // flip horizontally
  ctx.drawImage(
    sprite,
    Math.floor(knightFrame) * spriteWidth, 0,
    spriteWidth, spriteHeight,
    -knight.x - knight.width, knight.y, // flip X
    knight.width, knight.height
  );
} else {
  ctx.drawImage(
    sprite,
    Math.floor(knightFrame) * spriteWidth, 0,
    spriteWidth, spriteHeight,
    knight.x, knight.y,
    knight.width, knight.height
  );
}

ctx.restore();



// 🧙 Wizard - Larger, floating, on left side
const wizardX = 50;
const wizardY = canvas.height * 0.25 + wizardFloat;
const wizardScale = 3.5; // adjust for size
const frameW = wizardImg.naturalWidth / wizardFrameCount;
const frameH = wizardImg.height;

// 🧙 Wizard Animation (Flipped & Scaled Up)
ctx.save();

ctx.translate(wizardX + frameW * wizardScale, wizardY); // move context origin
ctx.scale(-1, 1); // flip horizontally

ctx.drawImage(
  wizardImg,
  wizardFrame * frameW, 0,             // source x, y
  frameW, frameH,                      // source width, height
  0, 0,                                // destination x (after flip)
  frameW * wizardScale, frameH * wizardScale // scaled size
);

ctx.restore();


if (knightTick % wizardAnimSpeed === 0) {
  wizardFrame = (wizardFrame + 1) % wizardFrameCount;
}



  if (fireProgress > 0 && fireProgress <= maxFireProgress) {
    const startX = canvas.width / 2 + 20;
    const startY = 100;
    const ratio = fireProgress / maxFireProgress;
    const currentX = startX + (fireTarget.x - startX) * ratio;
    const currentY = startY + (fireTarget.y - startY) * ratio;
    ctx.drawImage(fireImg, currentX - 64, currentY - 64, 128, 128);
    fireProgress++;
  } else {
    fireProgress = 0;
  }

  if (showQuestion) {
    drawQuestionAndAnswers();
  }
  

  applyCollisionBounds();
  detectNearbyDoor();
  knightTick++;
if (knightTick % frameSpeed === 0) {
  knightFrame = (knightFrame + 1) % 4; // 4 frames in idle/run sprite
}

  requestAnimationFrame(gameLoop);
}

function drawQuestionAndAnswers() {
  const q = questions[currentQuestionIndex];
  if (!q) return;

  // === Draw question bubble (right of wizard) ===
  const bubbleWidth = 500;
  const lines = q.text.length > 80 ? 3 : q.text.length > 40 ? 2 : 1;
  const bubbleHeight = 40 + lines * 25;
  const bubbleX = 220;
  const bubbleY = canvas.height * 0.25 - 30;

  // Rounded speech bubble
  ctx.fillStyle = "rgba(255, 255, 255, 0.9)";
  ctx.strokeStyle = "black";
  ctx.lineWidth = 2;
  ctx.beginPath();
  ctx.moveTo(bubbleX + 20, bubbleY);
  ctx.lineTo(bubbleX + bubbleWidth - 20, bubbleY);
  ctx.quadraticCurveTo(bubbleX + bubbleWidth, bubbleY, bubbleX + bubbleWidth, bubbleY + 20);
  ctx.lineTo(bubbleX + bubbleWidth, bubbleY + bubbleHeight - 20);
  ctx.quadraticCurveTo(bubbleX + bubbleWidth, bubbleY + bubbleHeight, bubbleX + bubbleWidth - 20, bubbleY + bubbleHeight);
  ctx.lineTo(bubbleX + 40, bubbleY + bubbleHeight);
  ctx.lineTo(bubbleX + 30, bubbleY + bubbleHeight + 15);
  ctx.lineTo(bubbleX + 20, bubbleY + bubbleHeight);
  ctx.quadraticCurveTo(bubbleX, bubbleY + bubbleHeight, bubbleX, bubbleY + bubbleHeight - 20);
  ctx.lineTo(bubbleX, bubbleY + 20);
  ctx.quadraticCurveTo(bubbleX, bubbleY, bubbleX + 20, bubbleY);
  ctx.closePath();
  ctx.fill();
  ctx.stroke();

  // Draw question text
  ctx.fillStyle = "black";
ctx.font = "18px Georgia";
ctx.textAlign = "left";
ctx.textBaseline = "top"; // this is important to prevent overlap
wrapText(ctx, q.text, bubbleX + 20, bubbleY + 20, bubbleWidth - 40, 24);


  // === Draw answers above each door ===
  ctx.font = "18px Arial";
  ctx.textAlign = "center";
  ctx.textBaseline = "top";

  q.options.forEach((opt, i) => {
    const door = doors[i];
    const x = door.x + 110;
    const padding = 10;
    const lineHeight = 22;

    const words = opt.split(" ");
    let lines = [], line = "";

    words.forEach(word => {
      const testLine = line + word + " ";
      const width = ctx.measureText(testLine).width;
      if (width > 180 && line) {
        lines.push(line.trim());
        line = word + " ";
      } else {
        line = testLine;
      }
    });
    lines.push(line.trim());

    // Calculate max line width
    const textWidths = lines.map(l => ctx.measureText(l).width);
    const maxTextWidth = Math.max(...textWidths);
    const boxWidth = maxTextWidth + padding * 2;
    const boxHeight = lines.length * lineHeight + padding * 2;
    const y = door.y - boxHeight - 10;

    // Draw black background box
    ctx.fillStyle = "rgba(0, 0, 0, 0.7)";
    ctx.fillRect(x - boxWidth / 2, y, boxWidth, boxHeight);

    // Draw text lines centered
    ctx.fillStyle = "white";
    lines.forEach((line, index) => {
      ctx.fillText(line, x, y + padding + index * lineHeight);
    });
  });
}



function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
  const words = text.split(' ');
  let line = '';
  for (let i = 0; i < words.length; i++) {
    const testLine = line + words[i] + ' ';
    const testWidth = ctx.measureText(testLine).width;
    if (testWidth > maxWidth && i > 0) {
      ctx.fillText(line, x, y);
      line = words[i] + ' ';
      y += lineHeight;
    } else {
      line = testLine;
    }
  }
  ctx.fillText(line, x, y);
}



function checkAnswer(selectedIndex) {
  const q = questions[currentQuestionIndex];
  if (!q) return;
  const correctIndex = q.correct;
  const explanation = q.explanation || "No explanation provided.";

  if (selectedIndex === correctIndex) {
    sound.correct.play();
    currentQuestionIndex++;
    if (currentQuestionIndex >= questions.length) {
      showEndBanner("🎉 CONGRATULATIONS! YOU WIN", true);
    } else {
      showQuestion = false;
    }
  } else {
    lives--;
    sound.fire.play();
fireProgress = 1;
fireTarget = { x: knight.x + knight.width / 2, y: knight.y + knight.height / 2 };
setTimeout(() => {
  showExplanationPopup(explanation, lives);
}, 800);

  }
}

function showEndBanner(message, win) {
  gameEnded = true;
  showQuestion = false;
  if (win) {
    sound.win.play();
  } else {
    sound.lose.play();
  }
  
  const scoreText = `${currentQuestionIndex}/${questions.length}`;
  fetch("submit_result.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `quiz_id=${window.quizId}&score=${encodeURIComponent(scoreText)}`
  }).then(res => res.json()).then(data => {
    if (!data.success) console.error("Failed to save result:", data.message);
  });

  ctx.fillStyle = "rgba(0,0,0,0.8)";
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  ctx.fillStyle = win ? "lime" : "red";
  ctx.font = "40px Arial";
  ctx.fillText(message, canvas.width / 2 - 250, canvas.height / 2);
  ctx.fillStyle = "white";
  ctx.font = "20px Arial";
  ctx.fillText(`Score: ${scoreText}`, canvas.width / 2 - 60, canvas.height / 2 + 40);
  ctx.fillText("Press R to Restart or M for Menu", canvas.width / 2 - 180, canvas.height / 2 + 80);

  document.addEventListener("keydown", (e) => {
    if (e.key === "r" || e.key === "R") location.reload();
    if (e.key === "m" || e.key === "M") {
      window.location.href = "subject.php?subject_id=" + window.subjectId;

    }
    

  });
}

function resumeGame() {
  isPaused = false;
  document.getElementById("pauseOverlay").style.display = "none";
  requestAnimationFrame(gameLoop);
}


function applyCollisionBounds() {
  const margin = 10;
  knight.x = Math.max(margin, Math.min(canvas.width - knight.width - margin, knight.x));
  knight.y = groundY; // ensure knight is locked to ground
  
}
function showExplanationPopup(explanation, lives) {
  const popup = document.getElementById("explanationPopup");
  document.getElementById("explanationText").innerText = `Explanation: ${explanation}`;
  document.getElementById("livesLeftText").innerText = `Lives left: ${lives}`;
  popup.style.display = "block";
}

function closeExplanation() {
  document.getElementById("explanationPopup").style.display = "none";
  showQuestion = false;
  if (lives <= 0) showEndBanner("☠️ YOU LOSE", false);
}

let assetsLoaded = 0;
knightIdleImg.onload = checkAllAssets;
knightRunImg.onload = checkAllAssets;

function checkAllAssets() {
  assetsLoaded++;
  if (assetsLoaded >= 2) {
    gameLoop();
  }
}


