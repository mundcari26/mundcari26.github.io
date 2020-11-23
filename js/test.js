/** 
 * JS Test, Para brindar funcionalidad desde / hacia el servidor
 * @author Ing. Luis Alberto Pérez González
 * @version alpha-1
 * @develop
 */

/**
 * Variable publica con la url del Gateway que recibe las peticiones al servidor.
 * @var {String}
 */
var GATEWAY = '../js/services/index.php';

/**
 * Variable publica que contiene el nombre de la Clase a invocar al servidor.
 * @var {String}
 */
var NOMBRE_CLASE = 'Registro';

/**
 * Variable publica que contiene el nombre de la Clase a invocar al servidor.
 * @var {String}
 */
var CATALOGOS = 'Catalogos';

/**
 * Funcion para listar las divisiones registradas
 * @returns {void}
 */
function getPreguntas()
{
    peticionJSON = JSON.stringify({
        'id': ('' + Math.random()).substring(2),
        'method': 'getPreguntas',
        'clase': CATALOGOS,
        'Params': []
    });
    $.ajax({
        method: 'POST',
        timeout: 30000,
        data: peticionJSON,
        dataType: 'json',
        url: GATEWAY,
        success: function (jsonRespuesta, estatusRespuesta, jqXHR)
        {
            reListDivisiones(jsonRespuesta, estatusRespuesta, jqXHR);
        },
        error: function (jqXHR, estatusError, textoError)
        {
            mostrarErrorJSON(jqXHR, estatusError, textoError);
        }
    });
}

/**
 * Funcion Listener para relistar las divisiones.
 * @param {object} jsonRespuesta Objeto de tipo JSON con la respuesta recibida del Servidor.
 * @param {string} estatusRespuesta Cadena de texto, con el estatus de la respuesta (succes)
 * @param {object} jqXHR Objeto XHR, con toda la traza de la respuesta.
 * @return {void}
 */
function reListDivisiones(jsonRespuesta, estatusRespuesta, jqXHR)
{
    if (jsonRespuesta.error) {
        mostrarError(jsonRespuesta.error, estatusRespuesta, jqXHR);
        return;
    }
    var preguntas = jsonRespuesta.result;
    var myQuestions = [];
    if (preguntas.length > 0) {
        for (i = 0; i < preguntas.length; i++) {
            myQuestions[i]={
                question: preguntas[i].Pregunta,
                answers: {
                    a: preguntas[i].incorrectaA,
                    b: preguntas[i].incorrectaB,
                    c: preguntas[i].incorrectaC,
                    d: preguntas[i].RcorrectaincorrectaA
                },
            correctAnswer: preguntas[i].RcorrectaincorrectaA
            }

        }
    } 
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
}
