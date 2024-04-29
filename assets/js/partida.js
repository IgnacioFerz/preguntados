// Variables
let currentQuestionIndex = 0;
let currentQuestionElement = null;
let timerInterval = null;
let timeRemaining = 15;
let score = 0;
let unansweredQuestions = [];
let correctAnswers = [];
let incorrectAnswers = [];
let jugador1Score = 0;
let jugador2Score = 0;
const partidaDataElement = document.getElementById('partida-data');
const partidaId = partidaDataElement.dataset.partidaId;
const jugador1Id = partidaDataElement.dataset.jugador1Id;
const jugador2Id = partidaDataElement.dataset.jugador2Id;

// Use the retrieved IDs in your JavaScript code
console.log("Partida ID:", partidaId);
console.log("Jugador 1 ID:", jugador1Id);
console.log("Jugador 2 ID:", jugador2Id);

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
        sendResultsToBackend();
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
function markAnswerAsIncorrect(jugadorId) {
    currentQuestionElement.classList.add('incorrect');
    if (jugadorId === jugador1Id) {
        jugador1Score--;
    } else {
        jugador2Score--;
    }
    incorrectAnswers.push(currentQuestionElement);

    // Añadir un retraso de 2 segundos antes de mostrar la siguiente pregunta
    setTimeout(function() {
        disableAnswerButtons();
        showNextQuestion();
    }, 2000);
}

// Función para marcar una respuesta como correcta
function markAnswerAsCorrect(questionElement, jugadorId) {
    questionElement.classList.add('correct');
    if (jugadorId === jugador1Id) {
        jugador1Score++;
    } else {
        jugador2Score++;
    }
    correctAnswers.push(questionElement);

    // Añadir un retraso de 2 segundos antes de mostrar la siguiente pregunta
    setTimeout(function() {
        disableAnswerButtons();
        showNextQuestion();
    }, 2000);
}

// Función para marcar una pregunta sin respuesta como incorrecta
function markUnansweredQuestionAsIncorrect(jugadorId) {
    unansweredQuestions.push(currentQuestionElement);
    currentQuestionElement.classList.add('incorrect');
    if (jugadorId === jugador1Id) {
        jugador1Score--;
    } else {
        jugador2Score--;
    }
    incorrectAnswers.push(currentQuestionElement);

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

function getIdUserOn()
{

}

// Función para enviar los resultados al back-end
function sendResultsToBackend() {
    // Prepare data to send to the backend
    const data = {
        partidaId: partidaId,
        jugador1Id: jugador1Id,
        jugador1Score: jugador1Score,
        jugador2Id: jugador2Id,
        jugador2Score: jugador2Score,
    };
    console.log(data)
    // Create XMLHttpRequest object for sending data
    const xhr = new XMLHttpRequest();

    xhr.open('POST', '/api/final'); // Replace with your actual endpoint
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Send the data as JSON
    xhr.send(JSON.stringify(data));

    // Handle the response from the backend
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Datos enviados correctamente');
            displayResultsScreen(); // Call function to display results
        } else {
            console.error('Error al enviar datos:', xhr.statusText);
        }
    };
    clearInterval(timerInterval);
}
// Function to display the results screen

function displayResultsScreen() {

    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.id = 'results-screen';
    document.body.appendChild(resultsContainer);

    // Display correct, incorrect, and unanswered questions counts
    const correctCount = correctAnswers.length;
    const incorrectCount = incorrectAnswers.length;
    const unansweredCount = unansweredQuestions.length;

    resultsContainer.innerHTML = `
    <h2>Results</h2>
    <p>Correct: ${correctCount}</p>
    <p>Incorrect: ${incorrectCount}</p>
    <p>Unanswered: ${unansweredCount}</p>
  `;


    // Display the questions with their status
    const questionsContainer = document.createElement('div');
    resultsContainer.appendChild(questionsContainer);

    for (const question of questionItems) {
        const status =
            correctAnswers.includes(question) ?
                'correct' :
                incorrectAnswers.includes(question) ?
                    'incorrect' :
                    'unanswered';

        questionsContainer.innerHTML += `
      <p class="${status}">${question.innerText}</p>
    `;
    }
    
    // Display winner information
    let winner;
    if (score > 0) {
        winner = 'You win!';
    } else {
        winner = 'You lose!';
    }
    resultsContainer.innerHTML += `<p>${winner}</p>`;

    // Add a button to redirect to the main menu
    const mainMenuButton = document.createElement('button');
    mainMenuButton.textContent = 'Main Menu';
    mainMenuButton.onclick = () => {
        // Remove results screen and reset variables
        document.body.removeChild(resultsContainer);
        currentQuestionIndex = 0;
        currentQuestionElement = null;
        timerInterval = null;
        timeRemaining = 15;
        score = 0;
        unansweredQuestions = [];
        correctAnswers = [];
        incorrectAnswers = [];

        // Show main menu or initial screen
        showNextQuestion();
    };
    resultsContainer.appendChild(mainMenuButton);
}

// Mostrar la pregunta inicial
showNextQuestion();