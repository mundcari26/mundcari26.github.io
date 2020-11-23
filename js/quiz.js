var myQuestions = [
    {
        question: '¿Quien creo a bugs bunny?',
        answers: {
            a: 'Axel Travel',
            b: 'Tex Averi',
            c: 'Michel Rouse '
        },
        correctAnswer: 'b' 
    },
    {
        question: 'En Scooby Doo, ¿Quien es el mejor amigo de Shaggy?',
        answers: {
            a: 'Pedro Picapiedra',
            b: 'Pato Lucas',
            c: 'Scooby-Doo'
        },
        correctAnswer: 'c' 
    },
    {
        question: '¿Quien dice la frace "¿Qué hay de nuevo, viejo?"?',
        answers: {
            a: 'Bugs Bunny',
            b: 'Pato Lucas',
            c: 'Gallo Claudio'
        },
        correctAnswer: 'a' 
    },
    {
        question: '¿Quien es el compañero de Ash Ketchum?',
        answers: {
            a: 'Bulbasaur',
            b: 'Charmander',
            c: 'Squirtle',
            d: 'Pikachu'
        },
        correctAnswer: 'd' 
    },{
        question: '¿Cuál fue el primer Pokémon que captura Ash Ketchum en la temporada 1?',
        answers: {
            a: 'Bulbasaur',
            b: 'Caterpie',
            c: 'Squirtle',
            d: 'Krabby'
        },
        correctAnswer: 'b' 
    }
];

var quizContainer = document.getElementById('quiz');
var resultsContainer = document.getElementById('results');
var submitButton = document.getElementById('submit');

generateQuiz(myQuestions, quizContainer, resultsContainer, submitButton);

function generateQuiz(questions, quizContainer, resultsContainer, submitButton){

    function showQuestions(questions, quizContainer){
        // we'll need a place to store the output and the answer choices
        var output = [];
        var answers;

        // for each question...
        for(var i=0; i<questions.length; i++){
            
            // first reset the list of answers
            answers = [];

            // for each available answer...
            for(letter in questions[i].answers){

                // ...add an html radio button
                answers.push(
                    '<label><input  type="radio" name="question'+i+'" value="'+letter+'">'+letter + ': '+ questions[i].answers[letter]+ '</label><br>'
                );
            }

            // add this question and its answers to the output
            output.push(
                '<div class="question">' + questions[i].question + '</div>'
                + '<div class="answers">' + answers.join('') + '</div>'
            );
        }

        // finally combine our output list into one string of html and put it on the page
        quizContainer.innerHTML = output.join('');
    }


    function showResults(questions, quizContainer, resultsContainer){
        
        // gather answer containers from our quiz
        var answerContainers = quizContainer.querySelectorAll('.answers');
        
        // keep track of user's answers
        var userAnswer = '';
        var numCorrect = 0;
        
        // for each question...
        for(var i=0; i<questions.length; i++){

            // find selected answer
            userAnswer = (answerContainers[i].querySelector('input[name=question'+i+']:checked')||{}).value;
            
            // if answer is correct
            if(userAnswer===questions[i].correctAnswer){
                // add to the number of correct answers
                numCorrect++;
                
                // color the answers green
                answerContainers[i].style.color = 'lightgreen';
            }
            // if answer is wrong or blank
            else{
                // color the answers red
                answerContainers[i].style.color = 'red';
            }
        }

        // show number of correct answers out of total
        resultsContainer.innerHTML = numCorrect + ' Correctos ' + questions.length;
    }

    // show questions right away
    showQuestions(questions, quizContainer);
    
    // on submit, show results
    submitButton.onclick = function(){
        showResults(questions, quizContainer, resultsContainer);
    }

}