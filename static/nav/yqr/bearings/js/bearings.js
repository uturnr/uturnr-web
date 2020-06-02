
var game = new Phaser.Game(640, 480, Phaser.AUTO, '', { preload: preload, create: create, update: update,  });

function preload() {

    game.add.text(265, 226, "LOADING", { font: '24px Arial', fill: '#fff'});
    game.load.image('red', 'assets/red.png');
    game.load.image('green', 'assets/green.png');
    game.load.image('white', 'assets/line.png');
    game.load.image('radar', 'assets/radar.png');
    game.load.image('grey', 'assets/grey.png')
    

}

    
function shuffleArray(array) {
    // re-orders an array
    for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
    }
    return array;
}

    
    var whiteline;
    var greenline;
    var mouseangle;
    var whitetext;
    var redtext;
    var greentext;
    var placetext;
    var firstcount = 0;
    var questionbank = [];
    //determine total number of questions based on number of places
    for (var i = 0; i < place.length; i++) { questionbank[i] = i; }
    var currentquestion = 0;
    var mastered = [];
    var lowanswer;
    var highanswer;
    var firsttry = true;
    var correct = false;
    var drawnangle;
    var correctangle;
    var currentplacebearing;
    var redline;
    var linelayer;
    var firsttryscore;
    var gamephase = 1;
    var timerEvent;
    var round = 1;
    var gameovertext;
    var masterquestion = 1;
    var numberofquestions = place.length;
    var correctscore;
    var correct;
    var correctcount = 0;
    var firstwrong = true;
    var newroundtext;
    var encouragement;
    var rotated = 217;     // Regina - rotated = 217 degrees


    

shuffleArray(questionbank);
    

function create() {
    


    game.physics.startSystem(Phaser.Physics.ARCADE);
    
    //background
    game.add.sprite(0, 0, 'radar');
    
    //create whiteline
    whiteline = game.add.sprite(320, 240, 'white');

    //create linelayer
    linelayer = game.add.group();

    //create whitetext
    whitetext = game.add.text(10, 60, "360", { font: 'bold 28px Arial', fill: '#fff' });
    whitetext.stroke = '#000000';
    whitetext.strokeThickness = 6;

    //create redtext
    redtext = game.add.text(10, 35, "", { font: 'bold 28px Arial', fill: '#ff0000' });
    redtext.stroke = '#000000';
    redtext.strokeThickness = 6;
    
    //create greentext
    greentext = game.add.text(10, 35, "", { font: 'bold 28px Arial', fill: '#00ff00' });
    greentext.stroke = '#000000';
    greentext.strokeThickness = 6;

    //create place
    placetext = game.add.text(10, 10, "", { font: 'bold 25px Arial', fill: '#fff' });
    placetext.stroke = '#000000';
    placetext.strokeThickness = 6;
    
    //create "Round"
    var roundtext = game.add.text(10, 325, "Round", { font: '14px Arial', fill: '#fff' });
    roundtext.stroke = '#000000';
    roundtext.strokeThickness = 6;
   
    //create roundscore
    roundscore = game.add.text(10, 340, 1, { font: 'bold 28px Arial', fill: '#fff' });
    roundscore.stroke = '#000000';
    roundscore.strokeThickness = 6;
    
    //create "Correct"
    var correcttext = game.add.text(10, 375, "Completed", { font: '14px Arial', fill: '#fff' });
    correcttext.stroke = '#000000';
    correcttext.strokeThickness = 6;
   
    //create correctscore
    correctscore = game.add.text(10, 390, 0, { font: 'bold 28px Arial', fill: '#fff' });
    correctscore.stroke = '#000000';
    correctscore.strokeThickness = 6;
    
    //create "First Try"
    var firsttrytext = game.add.text(10, 425, "First Try", { font: '14px Arial', fill: '#00ff00' });
    firsttrytext.stroke = '#000000';
    firsttrytext.strokeThickness = 6;
   
    //create firsttryscore
    firsttryscore = game.add.text(10, 440, 0, { font: 'bold 28px Arial', fill: '#00ff00' });
    firsttryscore.stroke = '#000000';
    firsttryscore.strokeThickness = 6;
    
    //create newroundtext
    newroundtext = game.add.text(320, 240, "", { font: 'bold 48px Arial', fill: '#fff', align: 'center' });
        newroundtext.anchor.set(0.5);
        newroundtext.stroke = '#000000';
        newroundtext.strokeThickness = 6;

    


    


    
    
    
    }

    
    
    
    
    
    








Math.radians = function(degrees) {
  return degrees * Math.PI / 180;
};
    
function update() {
    
    //check if round in progress    
    if (currentquestion < questionbank.length) {
    
    //set currentplacebearing to the current answer
    currentplacebearing = parseInt(placebearing[questionbank[currentquestion]].substring(0,3));
            
     //show placetext
    placetext.setText(place[questionbank[currentquestion]]);

        //if round over
        } else {
            
        round++;
        newroundtext.setText("Round\n" + round);
        timerEvent = game.time.events.add(Phaser.Timer.SECOND * 2, hideround, this);
        correctcount = 0;
        //remove mastered questions
        var k = 0
            
        while (mastered.length > 0) {
            questionbank.splice(mastered[0]-k,1)
            mastered.splice(0,1)
            k++;

        }
        currentquestion = 0;
        firstcount = 0;
            
        }
    
            
    //set drawnangle based on pointer
    drawnangle = game.physics.arcade.angleToPointer(whiteline) - 1.57;
    
    // get the angle of the mouse
    mouseangle =        Math.round(Math.atan2(game.input.y - 240,  game.input.x - 320 ) * (180/Math.PI));
    
    //show current round
    roundscore.setText(round);

    //show current correctscore
    correctscore.setText(correctcount + " / " + questionbank.length);
    
    //show current firsttryscore
    firsttryscore.setText(firstcount + " / " + questionbank.length);
   
    // get correctangle (for green line) from answer
    correctangle = currentplacebearing
    
    //adjust correctangle for rotation
    if (correctangle >= 0 && correctangle <= rotated) {
        correctangle = correctangle + (360 - rotated)
    } else [
        correctangle = correctangle - rotated
    ]
    //convert place bearing to correctangle (computer angle for green line)
    if (correctangle >= 0 && correctangle <= 270) {
    correctangle = correctangle - 90
    }
    if (correctangle <= 360 && correctangle > 270) {
    correctangle = correctangle - 450
    }
    


    

    

    // turn the angle of the mouse into an aviation bearing
    if (mouseangle >= 0 && mouseangle <= 180) {
    mouseangle = mouseangle + 90;
    }
    if (mouseangle <= -1 && mouseangle >= -90) {   
    mouseangle = mouseangle + 90;
    }
    if (mouseangle <= -91 && mouseangle >= -180) {  
    mouseangle = mouseangle + 450;
    }
    
    // correct the angle for radar displays that do not point north
    // Regina - rotated 220 degrees, see global variables

    if (mouseangle >= 0 && mouseangle <= (360-rotated)) {
    mouseangle = mouseangle + rotated;
    } else {
    mouseangle = mouseangle - (360-rotated);
    }
    
    // aviation bearing formatting
    if (mouseangle == 0) {  
    mouseangle = 360;
    }
    if (mouseangle >= 1 && mouseangle <= 9) {  
    mouseangle = "00" + mouseangle;
    }
    if (mouseangle >= 10 && mouseangle <= 99) {  
    mouseangle = "0" + mouseangle;
        }
    

    
    
    
if (gamephase == 1) {
    
    //greentext shows nothing
    greentext.setText("");
    
    //whitetext shows current bearing
    if(typeof mouseangle !== 'undefined'){
    whitetext.setText(mouseangle);
    }
    
    //whiteline angles toward mouse
    whiteline.rotation = drawnangle
    
    //on click
    game.input.onUp.add(function()
    {
        
        
        
        if(gamephase == 1) {
    
    //checking if selection within 5 degrees of answer
    if (mouseangle >= (currentplacebearing-5) && mouseangle <= (currentplacebearing + 5))
{ 
    //set correct to true
    correct = true;
    
    //up correctcount by one
    if (correct == true) {
    correctcount++;
    correct = false
    firstwrong = true;
    }
    
    //add one to score if first try
    if (firsttry == true) {
    firstcount++; //score!
    mastered.push(currentquestion); //add the question number to the mastered list
    firsttry = false;
    }

    //show correct answer in green
    redtext.setText("");
    greentext.setText(placebearing[questionbank[currentquestion]]);
    
    //show chosen answer in white
    whitetext.setText(mouseangle);

    //show greenline at correct bearing
    greenline = linelayer.create(320, 240, 'green');
    greenline.rotation = Math.radians(correctangle) - 1.57

    

    
    //move to next gamephase, unless game is over
    if (firstcount != questionbank.length) {
    gamephase = 2
    delayTimer();
        //game done?
    } else {
        
        gamephase = 2
        timerEvent = game.time.events.add(Phaser.Timer.SECOND * 2, alldone, this);
        
        
    }
    
} else {
    
    //set firsttry to false
    firsttry = false;
    
    //show redline where clicked
    if (firstwrong == true) {
    redline = linelayer.create(320, 240, 'red');
    firstwrong = false;}
    redline.rotation = drawnangle;
    
    //show wrong answer in red
    redtext.setText(mouseangle);

    
}
            
        }
        
    });


    
    
}
    
    if (gamephase == 2) {
        
        
        
        
    }
    
        if (gamephase == 3) {
        
        
    }


    
}
  function nextQuestion() {

      //get rid of those blasted red and green lines
      destroyGroup(linelayer);
      
      //move to the next question
      if (firsttry == false) {
      currentquestion++; //score!
      masterquestion++;
      firsttry = true;
      }
      
            

}  
function alldone()
    {
    var percentage = Math.round(100 * (numberofquestions / masterquestion));
        var s = "s";
        if (round == 1) { 
        s = "";
        }
        gamephase = 4;
        game.add.sprite(0, 0, 'grey');
        gameovertext = game.add.text(320, 240, "", { font: 'bold 32px Arial', fill: '#fff', align: 'center' });
        gameovertext.anchor.set(0.5);
        gameovertext.stroke = '#000000';
        gameovertext.strokeThickness = 6;
        if (percentage == 100) {
            encouragement = "Excellent!"
        } else if (percentage > 80) {
            encouragement = "Well Done!"
        } else {
            encouragement = "Keep Practising!"
        }
        gameovertext.setText(encouragement + "\nYou chose the correct bearing\non your first try " + percentage + "% of the time.\nYou completed the game in " + round + " round" + s +  ".");

    }
    
    function hideround()
    {

        newroundtext.setText("");

    }
    
function delayTimer()
    {
        
        timerEvent = game.time.events.add(Phaser.Timer.SECOND * 2, nextQuestion, this);
        
    }
    
    // I found this function on the internet and tweaked it. it deletes the green and red lines
    function destroyGroup(group, name)
    {
        
        var name = name  || 'undefined';
        var length = group.length;
        while (group.length > 0)
        {
            obj = group.getAt(0);

            if(obj.destroy) {
                obj.destroy();
        } else {
                group.removeBetween(-1,1);
            }
        

        }
        gamephase = 1;
    }
