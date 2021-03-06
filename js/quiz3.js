var myQuestions = [
    {
        question: '¿Como se llama la mascota del Marvin el Marciano?',
        answers: {
            a: 'Bugs',
            b: 'Fredy',
            c: 'Ronald',
            d: 'k-9'
        },
        correctAnswer: 'd' 
    },{
        question: '¿Quien es el dueño de la cantera en la que trabaja Pedro Picapiedra?',
        answers: {
            a: 'Señor Rajuela',
            b: 'Mr. Slate',
            c: 'Pablo',
        },
        correctAnswer: 'a' 
    },{
        question: 'En los picapiedras, ¿Quien es la esposa de Pablo Mármol?',
        answers: {
            a: 'Betty',
            b: 'Vilma',
            c: 'Pebbles '
        },
        correctAnswer: 'a' 
    },{
        question: 'En los picapiedras, ¿Quien dice la frase, "!Hasta la vista, tontos¡"?',
        answers: {
            a: 'Gazú',
            b: 'Pablo',
            c: 'Bam-bam',
            d: 'Vilma'
        },
        correctAnswer: 'a' 
    },{
        question: '¿Quien dice la frase, "Esto me pone muy, muy furioso"?',
        answers: {
            a: 'Silvestre',
            b: 'Taz',
            c: 'Marvin',
        },
        correctAnswer: 'c' 
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
    },
    {
        question: 'En los Los Chicos del Barrio, ¿Cuál es el verdadero nombre de Número 3?',
        answers: {
            a: 'Kuki Sanban',
            b: 'Cree Olivera',
            c: 'Susana Sanban'
        },
        correctAnswer: 'a' 
    },
    {
        question: 'En los Looney Toons, ¿Con quien se quiere casar Miss Prissy?',
        answers: {
            a: 'Gallo Claudio',
            b: 'Pato Lucas',
            c: 'Elmer',
        },
        correctAnswer: 'a' 
    },
    {
        question: '¿Cuál fue el primer Pokémon que captura Ash Ketchum en la temporada 1?',
        answers: {
            a: 'Bulbasaur',
            b: 'Caterpie',
            c: 'Squirtle',
            d: 'Krabby'
        },
        correctAnswer: 'b' 
    },
    {
        question: 'En los Los Chicos del Barrio, ¿Cómo se llama la hermana mayor de Número 5?',
        answers: {
            a: 'Jessy Olivera',
            b: 'Cree Olivera',
            c: 'Fabiola Olivera'
        },
        correctAnswer: 'b' 
    },
    
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
        question: 'En los Looney Toons, ¿Quien dice la frase, "¡..., digo...! ♪ Doo-Dah! Doo-Dah! ♫"?',
        answers: {
            a: 'Gallo Claudio',
            b: 'Porky',
            c: 'Piolín',
            d: 'El gato Silvestre'
        },
        correctAnswer: 'a' 
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
        question: 'En los Looney Toons, ¿A quien siempre intenta comerse el gato Silvestre?',
        answers: {
            a: 'Piolín',
            b: 'Taz',
            c: 'Bugs Bunny',
        },
        correctAnswer: 'a' 
    },
    
    {
        question: 'En los Looney Toons, ¿Que oficio  tiene Elmer Gruñón?',
        answers: {
            a: 'Cocinero',
            b: 'Cazador',
            c: 'Cantinero',
        },
        correctAnswer: 'b' 
    },
    {
        question: '¿Quien dice la frase "¿Qué hay de nuevo, viejo?"?',
        answers: {
            a: 'Bugs Bunny',
            b: 'Pato Lucas',
            c: 'Gallo Claudio'
        },
        correctAnswer: 'a' 
    },
    
    {
        question: 'En los Looney Toons, ¿Quien dice la frase, ""Ándale! Ándale! Arriba! Arriba!""?',
        answers: {
            a: 'Porky',
            b: 'Speedy Gonzáles',
            c: 'Bugs Bunny',
        },
        correctAnswer: 'a' 
    },
    {
        question: 'En los Los Chicos del Barrio, ¿Cómo se llama la hermano menor de Número 2?',
        answers: {
            a: 'Kike González',
            b: 'Jhonny González',
            c: 'Tommy González,'
        },
        correctAnswer: 'c' 
    },
    {
        question: 'En los Looney Toons, ¿Cuál es el nombre del Demonio de Tasmania?',
        answers: {
            a: 'Silvestre',
            b: 'Taz',
            c: 'Sam',
        },
        correctAnswer: 'b' 
    },
    {
        question: 'En los Looney Toons, ¿Qué animal es Pepe Le Pew?',
        answers: {
            a: 'Zorrillo',
            b: 'Toro',
            c: 'Ratón',
        },
        correctAnswer: 'a' 
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