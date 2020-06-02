var game = new Phaser.Game(640, 480, Phaser.AUTO, '', { preload: preload, create: create, update: update,  });

function preload() {

        game.add.text(265, 226, "LOADING", { font: '24px Arial', fill: '#fff'});
        game.load.image('radar', 'assets/radar.png');
        game.load.image('radar2', 'assets/radar2.png');
        game.load.image('pps', 'assets/pps.png');
        game.load.image('trail1', 'assets/trail1.png');
        game.load.image('trail2', 'assets/trail2.png');
        game.load.image('trail3', 'assets/trail3.png');
        game.load.image('trail4', 'assets/trail4.png');
        game.load.image('trail5', 'assets/trail5.png');
        game.load.image('trail6', 'assets/trail6.png');
        game.load.image('trail7', 'assets/trail1.png');
        game.load.image('trail8', 'assets/trail1.png');
        game.load.image('greybox','assets/greybox.png');




}

    var radar;    
    var pps1;
        var pps2;
        var xbank = [100,150,200,250,300,350,400,450,500,550];
        var ybank = [50,100,150,200,250,300,350,400];
        var ppsx;
        var ppsy;
        var trailangle;
        var clicked = false;
        var trail1;
        var trail2;
        var angle;
        var trailimages = ['trail1', 'trail2', 'trail3', 'trail4', 'trail5', 'trail6', 'trail7', 'trail8'];
        var randomtrail;
        var acidtext1;
        var acidtext2;
        var reg1;
        var reg2;
        var ac;
        var alt1;
        var alt2diff;
        var alt2;
        var traillayer;
        var typetext;
    var type1;
    var type2;
    var typebutton;
var timebutton;
var counter = 0;
var starttext;
var timer;
var timertext;
var timerrunning = false;
var radar2;

    
function create() {
    


    game.physics.startSystem(Phaser.Physics.ARCADE);
    
    //background
    radar = game.add.sprite(0, 0, 'radar');
    radar.inputEnabled = true;
    radar.events.onInputDown.add(refresh, this);
    
    radar2 = game.add.sprite(0, 0, 'radar2');

    
 game.time.events.add(1000, function(){   
    
    radar2.alpha = 0
     
 });

    
game.time.events.add(1000, function(){
    
    //create traillayer
    traillayer = game.add.group();
    
    //initial 1 randomness
    ppsx = xbank[Math.floor(Math.random()*(9-0+1)+0)];
    ppsy = ybank[Math.floor(Math.random()*(7-0+1)+0)];
    trailangle = Math.random()*Math.PI*2;
    randomtrail = trailimages[Math.floor(Math.random()*(7-0+1)+0)];
    ac = Math.floor(Math.random()*(2-0+1)+0);
    alt1 = Math.floor(Math.random()*(37-9+1)+9)
    
    //format altitude
    if (alt1 < 10) {
        alt1 = "00" + alt1
    } else {
        alt1 = "0" + alt1
    }
    
    //draw pps1
    pps1 = game.add.sprite(ppsx,ppsy, 'pps');
    pps1.anchor.set(0.5);

    //draw trail1
    trail1 = traillayer.create(ppsx, ppsy, randomtrail);
    trail1.rotation = trailangle
    
    //draw acidtext1
    reg1 = registration[ac]
    type1 = type[ac]
    acidtext1 = game.add.text(ppsx+12, ppsy-9, reg1 + "\n" + alt1 + " " + speed[ac], { font: '14px Arial', fill: 'rgb(255, 255, 0)' });
    acidtext1.stroke = '#000000';
    acidtext1.strokeThickness = 3;
    acidtext1.lineSpacing = -8;
        
    //initial 2 randomness
    ppsx = xbank[Math.floor(Math.random()*(9-0+1)+0)];
    ppsy = ybank[Math.floor(Math.random()*(7-0+1)+0)];
    trailangle = Math.random()*Math.PI*2;
    
    randomtrail = trailimages[Math.floor(Math.random()*(7-0+1)+0)];
    ac = Math.floor(Math.random()*(registration.length));
    alt2diff = Math.floor(Math.random()*(10-(-10)+1)-10);
    alt2 = parseInt(alt1) + alt2diff;
    
    //force alt2 within bounds
    while (alt2 < 9 || alt2 > 37) {
    alt2diff = Math.floor(Math.random()*(10-(-10)+1)-10);
    alt2 = parseInt(alt1) + alt2diff;
    }
    
    //format altitude
    if (alt2 < 10) {
        alt2 = "00" + alt2
    } else {
        alt2 = "0" + alt2
    }
    

    //draw pps2
    pps2 = game.add.sprite(ppsx,ppsy, 'pps');
    pps2.anchor.set(0.5);
    while (pps2.x == pps1.x && pps2.y ==  pps1.y) {
    ppsx = xbank[Math.floor(Math.random()*(9-0+1)+0)];
    ppsy = ybank[Math.floor(Math.random()*(7-0+1)+0)];
    pps2.x = ppsx;
    pps2.y = ppsy;
    }
        
    //draw trail2
    trail2 = game.add.sprite(ppsx,ppsy, 'trail1');
    trail2.rotation = trailangle
    
    //draw acidtext2
    reg2 = registration[ac]
    type2 = type[ac]
    acidtext2 = game.add.text(ppsx+12, ppsy-9, reg2 + "\n" + alt2 + " " + speed[ac], { font: '14px Arial', fill: 'rgb(255, 255, 0)' });
    acidtext2.stroke = '#000000';
    acidtext2.strokeThickness = 3;
    acidtext2.lineSpacing = -8;
    while (reg2 == reg1) {
    ac = Math.floor(Math.random()*(registration.length));
    reg2 = registration[ac]
    type2 = type[ac]
    acidtext2.setText(reg2 + "\n" + alt2 + " " + speed[ac]);
    }
    
    //draw typebutton
    typebutton = game.add.sprite(5, 425, 'greybox');
    typebutton.inputEnabled = true;
    typebutton.input.useHandCursor = true;
    typebutton.events.onInputDown.add(reveal, this);


    //draw typetext
    typetext = game.add.text(64, 453, "REVEAL\nTYPES", { font: '600 14px Raleway, Arial, sans-serif', fill: 'rgb(114, 114, 114)', align: "center" });
    typetext.lineSpacing = -5;
    typetext.stroke = '#000000';
    typetext.strokeThickness = 3;
    anchor(typetext);
    
    //draw timebutton
    timebutton = game.add.sprite(518, 425, 'greybox');
    timebutton.inputEnabled = true;
    timebutton.input.useHandCursor = true;
    timebutton.events.onInputDown.add(timertoggle, this);
    
    //draw starttext
    starttext = game.add.text(577, 453, "START\nTIMER", { font: '600 14px Raleway, Arial, sans-serif', fill: 'rgb(114, 114, 114)', align: "center" });
    starttext.lineSpacing = -5;
    starttext.stroke = '#000000';
    starttext.strokeThickness = 3;
    anchor(starttext);
    
    //draw timer
    timertext = game.add.text(579, 453, "", { font: 'bold 22px Arial', fill: 'rgb(255, 255, 255)', align: "center" });
    timertext.stroke = '#000000';
    timertext.strokeThickness = 3;
    
    //draw radar2 "curtain"
    radar2 = game.add.sprite(0, 0, 'radar2');
        game.add.tween(radar2).to( { alpha: 0 }, 1000, Phaser.Easing.Linear.None, true);
}, this);
    


    this.game.input.keyboard.addKeyCapture([Phaser.Keyboard.SPACEBAR]);

    // add keyboard controls
    var spacekey = this.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR);
    spacekey.onDown.add(refresh);
    
    var rkey = this.input.keyboard.addKey(Phaser.Keyboard.R);
    rkey.onDown.add(reveal);
    
    var tkey = this.input.keyboard.addKey(Phaser.Keyboard.T);
    tkey.onDown.add(timertoggle);



 
}
    
    
    
    
    
    









    
function update() {
    
    

        }
                            


    
    
    
    function reveal () {
    //set typetext
    typetext.setText(reg1 + " - " + type1 + "\n" + reg2 + " - " + type2);
    typetext.addColor('#ffffff', 0);
    typetext.cssFont = '14px Arial';
    anchor(typetext);
        
}
    
    function refresh () {
    clicked = true
    if (clicked == true) {
    
    //reset reveal types box
    typetext.setText("REVEAL\nTYPES");
    typetext.addColor('rgb(114, 114, 114)', 0);
            typetext.cssFont = '600 14px Raleway, Arial, sans-serif';

        
    //click 1 randomness
    ppsx = xbank[Math.floor(Math.random()*(9-0+1)+0)];
    ppsy = ybank[Math.floor(Math.random()*(7-0+1)+0)];
    trailangle = Math.random()*Math.PI*2;

    randomtrail = trailimages[Math.floor(Math.random()*(7-0+1)+0)];
    ac = Math.floor(Math.random()*(2-0+1)+0);
    alt1 = Math.floor(Math.random()*(37-9+1)+9)
    
    //format altitude
    if (alt1 < 10) {
        alt1 = "00" + alt1
    } else {
        alt1 = "0" + alt1
    }
    
    //move pps1
    pps1.x = ppsx
    pps1.y = ppsy

    //draw trail1
    trail1.destroy();
    trail1 = traillayer.create(ppsx, ppsy, randomtrail);
    trail1.rotation = trailangle

    
    //draw acidtext1
    if (trail1.rotation > 5 || trail1.rotation < 0.5) {
    acidtext1.x = ppsx-60
    acidtext1.y = ppsy-9
    } else {
    acidtext1.x = ppsx+12
    acidtext1.y = ppsy-9
    }
    reg1 = registration[ac]
    type1 = type[ac]
    acidtext1.setText(reg1 + "\n" + alt1 + " " + speed[ac]);
        
    //click 2 randomness
    ppsx = xbank[Math.floor(Math.random()*(9-0+1)+0)];
    ppsy = ybank[Math.floor(Math.random()*(7-0+1)+0)];
    trailangle = Math.random()*Math.PI*2;

    randomtrail = trailimages[Math.floor(Math.random()*(7-0+1)+0)];
    ac = Math.floor(Math.random()*(registration.length));
    alt2diff = Math.floor(Math.random()*(10-(-10)+1)-10);
    alt2 = parseInt(alt1) + alt2diff;
    
    //force alt2 within bounds
    while (alt2 < 9 || alt2 > 37) {
    alt2diff = Math.floor(Math.random()*(10-(-10)+1)-10);
    alt2 = parseInt(alt1) + alt2diff;
    }
    
    //format altitude
    if (alt2 < 10) {
        alt2 = "00" + alt2
    } else {
        alt2 = "0" + alt2
    }

    //move pps2
    pps2.x = ppsx
    pps2.y = ppsy
    while (pps2.x == pps1.x && pps2.y ==  pps1.y) {
    ppsx = xbank[Math.floor(Math.random()*(9-0+1)+0)];
    ppsy = ybank[Math.floor(Math.random()*(7-0+1)+0)];
    pps2.x = ppsx;
    pps2.y = ppsy;
    }
        
    //draw trail2
    trail2.destroy();
    trail2 = traillayer.create(ppsx, ppsy, randomtrail);
    trail2.rotation = trailangle;
    
    //draw acidtext2
    if (trail2.rotation > 5 || trail2.rotation < 0.5) {
    acidtext2.x = ppsx-60
    acidtext2.y = ppsy-9
    } else {
    acidtext2.x = ppsx+12
    acidtext2.y = ppsy-9
    }
    reg2 = registration[ac]
    type2 = type[ac]
    acidtext2.setText(reg2 + "\n" + alt2 + " " + speed[ac]);
    while (reg2 == reg1) {
    ac = Math.floor(Math.random()*(registration.length));
    reg2 = registration[ac];
    type2 = type[ac];
    acidtext2.setText( reg2 + "\n" + alt2 + " " + speed[ac]);
        
    }

            clicked = false;

    }
    }

function starttimer() {

starttext.setText("");
counter = 0
timertext.addColor('#ffffff', 0)
timer = game.time.events.loop(Phaser.Timer.SECOND/10, updateTimer, this);
    timerrunning = true;
    
}

function stoptimer() {

  game.time.events.remove(timer);
  timertext.addColor('rgb(114, 114, 114)', 0);
    timerrunning = false;

  
    
}

function updateTimer() {
    counter++;
    timertext.setText(parseFloat(counter/10).toFixed(1));
    anchor(timertext);

}

function timertoggle() {
    
if (timerrunning == false) {
    starttimer();
} else {
    stoptimer();
}
    
}

function anchor(a) {
    
    a.anchor.x = Math.round(a.width * 0.5) / a.width;
    a.anchor.y = Math.round(a.height * 0.5) / a.height;
}
