import axios from "axios";
// Variables
let currentQuestionIndex = 0;
let currentQuestionElement = null;
let timerInterval = null;
let timeRemaining = 15;
let puntuacion = 0;
let unansweredQuestions = [];
let correctAnswers = [];
let incorrectAnswers = [];
const partidaDataElement = document.getElementById('partida-data');
const partidaId = partidaDataElement.dataset.partidaId;
let respuesta = '';
let userId = await getIdUserOn();

console.log("Partida ID:", partidaId);


// Obtener la lista de preguntas
let questionList = document.getElementById('question-list');
let questionItems = questionList.querySelectorAll('.question-item');


// Función para mostrar la siguiente pregunta
function showNextQuestion() {
    // Ocultar la pregunta anterior
    if (currentQuestionElement) {
        currentQuestionElement.style.display = 'none';
    }

    // Restablecer el temporizador y actualizar la visualización del tiempo
    resetTimer();
    updateTimerDisplay();

    // Comprobar si se ha llegado al final del juego
    if (currentQuestionIndex === questionItems.length) {
        // Enviar los resultados al back-end
        seeResult();
        return;
    }
    currentQuestionElement = questionItems[currentQuestionIndex];

    // Mostrar la siguiente pregunta
    currentQuestionElement.style.display = 'block';

    // **Incrementar el índice aquí para evitar una verificación innecesaria en el bucle**
    currentQuestionIndex++;
}

// Función para restablecer el temporizador
function resetTimer() {
    timeRemaining = 15;
    clearInterval(timerInterval);
    timerInterval = setInterval(updateTimerDisplay, 1000);
}

// Función para actualizar la visualización del temporizador
function updateTimerDisplay() {
    timeRemaining--;
    document.getElementById('timer').textContent = timeRemaining + ' s';

    // Comprobar si se ha agotado el tiempo y marcar la respuesta como incorrecta
    if (timeRemaining === 0) {
        markUnansweredQuestionAsIncorrect();
        showNextQuestion();
    }
}

// Función para deshabilitar los botones de respuesta
function disableAnswerButtons() {
    let answerButtons = currentQuestionElement.querySelectorAll('.answer-button');
    for (let button of answerButtons) {
        button.disabled = true;
    }
}

// Función para marcar una respuesta como incorrecta
function markAnswerAsIncorrect() {
    currentQuestionElement.classList.add('incorrect');
    puntuacion--;
    respuesta = 'incorrecta';
    incorrectAnswers.push(currentQuestionElement);
    // Añadir un retraso de 2 segundos antes de mostrar la siguiente pregunta
    setTimeout(function() {
        disableAnswerButtons();
        showNextQuestion();
        updateQuestion();
    }, 2000);
    sendResultsToBackend()
}

// Función para marcar una respuesta como correcta
function markAnswerAsCorrect(questionElement) {
    questionElement.classList.add('correct');
    puntuacion++;
    respuesta = 'correcta';
    correctAnswers.push(questionElement);

    // Añadir un retraso de 2 segundos antes de mostrar la siguiente pregunta
    setTimeout(function() {
        disableAnswerButtons();
        showNextQuestion();
        updateQuestion();
    }, 2000);
    sendResultsToBackend()
}

// Función para marcar una pregunta sin respuesta como incorrecta
function markUnansweredQuestionAsIncorrect() {
    unansweredQuestions.push(currentQuestionElement);
    currentQuestionElement.classList.add('incorrect');
    puntuacion--;
    respuesta = 'incorrecta';
    incorrectAnswers.push(currentQuestionElement);
    showNextQuestion();
    updateQuestion();
    sendResultsToBackend();
}

// Manejo de clics en las preguntas
questionList.addEventListener('click', function(event) {
    // Comprobar si el elemento cliqueado es un botón de respuesta
    if (event.target.classList.contains('answer-button')) {
        // Deshabilitar los botones de respuesta temporalmente
        let answerButtons = currentQuestionElement.querySelectorAll('.answer-button');
        for (let button of answerButtons) {
            button.disabled = true;
        }

        // Obtener la respuesta seleccionada
        let selectedAnswer = event.target.dataset.answer;

        // Comprobar si la respuesta es correcta
        let correctAnswer = event.target.closest('.question-item').dataset.correctAnswer;

        if (selectedAnswer === correctAnswer) {
            markAnswerAsCorrect(event.target.closest('.question-item'));
        } else {
            markAnswerAsIncorrect(event.target.closest('.question-item'));
        }

        // Detener el temporizador
        clearInterval(timerInterval);
    }
});

async function updateQuestion(resultElement){
    try
    {
        const response = await axios.post('/api/update/question', {
            partidaId: parseInt(partidaId),
            userId: userId
        });
        if (response.data.message) { // Hay un ganador
            let message = document.createElement('p');
            message.classList.add('lead', 'text-center', 'text-success', 'mt-3');
            message.textContent = response.data.message;

            let button = document.createElement('button');
            button.classList.add('btn', 'btn-primary', 'mt-3');
            button.textContent = 'Volver a la página principal';
            button.onclick = function() {
                window.location.href = '/principal';
            };

            resultElement.innerHTML = '';
            resultElement.appendChild(message);
            resultElement.appendChild(button);
            currentQuestionElement.style.display = 'none';
            clearInterval(timerInterval);
        }
    }catch (error) {
        console.error('Error al actualizar la posicion de la pregunta:', error);
        return null; // Devuelve null en caso de error
    }
}
updateQuestion(document.getElementById('result'));
async function getIdUserOn() {
    try {
        const response = await axios.get('/api/user/id');
        const userIdObject = response.data; // Suponiendo que response.data es el objeto
        const userId = userIdObject.userId; // Extrae la propiedad userId
        console.log('ID de usuario:', userId);
        return userId; // Devuelve el userId extraído
    } catch (error) {
        console.error('Error al recuperar el ID de usuario:', error);
        return null; // Devuelve null en caso de error
    }
}

// Función para enviar los resultados al back-end
async function sendResultsToBackend() {
    try {
        const data = {
            partidaId: parseInt(partidaId),
            userId: userId,
            puntuacion: puntuacion,
            respuesta: respuesta,
        };
        const jsonData = JSON.stringify(data);
        const response = await axios.post('/api/final/result', jsonData);

        if (response.status === 200 || response.status === 201 ) {
            console.log('Datos enviados correctamente');
        } else {
            console.error('Error al enviar datos:', response.statusText);
        }
    } catch (error) {
        console.error('Error al enviar datos:', error);
    } finally {
        clearInterval(timerInterval);
    }
}
async function seeResult(){
    const response = await axios.post('/api/final');

    if (response.status === 200 || response.status === 201 ) {
        console.log('Datos enviados correctamente');
    } else {
        console.error('Error al enviar datos:', response.statusText);
    }
}
// Mostrar la pregunta inicial
showNextQuestion();