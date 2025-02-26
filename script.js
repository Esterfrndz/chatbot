//Se inicia un evento cúando el formulario se envía
document
  .getElementById("chat-form")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

    // Obtiene el contenido del input

    const inputField = document.getElementById("inputField");
    const message = inputField.value;
    if (message.trim() === "") return; // Evita mensajes vacíos

    //Agregar el mensaje a el contenedor "conversation", luego limpia el campo del input
    const conversation = document.getElementById("conversation");
    conversation.innerHTML += `<strong class="user-message" >Tú:</strong> ${message}<br>`;
    inputField.value = "";

    //Solicitud HTTP POST
    fetch("chatbot.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "input=" + encodeURIComponent(message),
    })
      //Manejo de respuesta
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      //Manejo de datos JSON
      .then((data) => {
        if (data.response) {
          conversation.innerHTML += `<strong class="bot-message"">Chatbot:</strong> ${data.response}<br>`;
          document.getElementById('chatbotSound').play(); // Reproduce el sonido
        } else if (data.error) {
          conversation.innerHTML += `<strong>Chatbot:</strong> Error de Gemini: ${data.error}<br>`;
        } else {
          conversation.innerHTML += `<strong>Chatbot:</strong> Respuesta inesperada: ${JSON.stringify(
            data
          )}<br>`;
        }
      })
      //Manejo de errores
      .catch((error) => {
        conversation.innerHTML += `<strong style="color: red;" >Chatbot:</strong> Error: ${error.message}<br>`;
      });
  });
