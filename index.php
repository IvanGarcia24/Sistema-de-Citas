<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Médica Medis</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Nothing+You+Could+Do&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header-with-slider">
        <div class="header-slides">
            <div class="header-slide active" style="background-image: url('assets/image/general.jpg');">
                <div class="header-content">
                    <h1>Clínica Médica Salud Integral</h1>
                    <p class="slogan">Tu salud es nuestra prioridad</p>
                </div>
            </div>
            <div class="header-slide active" style="background-image: url('assets/image/pediatria.jpg');">
                <div class="header-content">
                    <h1>Atención Pediátrica de Calidad</h1>
                    <p class="slogan">Cuidamos a los más pequeños con amor y profesionalismo</p>
                </div>
            </div>
            <div class="header-slide active" style="background-image: url('assets/image/ginecologia.jpg');">
                <div class="header-content">
                    <h1>Especialistas en Salud Femenina</h1>
                    <p class="slogan">Acompañamos cada etapa de tu vida con confianza y respeto</p>
                </div>
            </div>
            <div class="header-slide active" style="background-image: url('assets/image/geriatria.jpg');">
                <div class="header-content">
                    <h1>Cuidado Integral para Adultos Mayores</h1>
                    <p class="slogan">Bienestar y calidad de vida en cada etapa</p>
                </div>
            </div>
        </div>
        <div class="header-buttons">
            <a href="#" class="login-button">Iniciar Sesión</a>
            <a href="#" class="register-button">Registrarse</a>
        </div>         
    </header>

    <nav class="semi-transparent-nav">
        <ul>
            <li><a href="#servicios">Servicios</a></li>
            <li><a href="#nosotros">Nosotros</a></li>
        </ul>
    </nav>

    <div class="container">
        <section id="servicios">
            <h2>Nuestros Servicios</h2>
            <div class="services">
                <div class="service-card" style="background-image: url('assets/image/servicioGen.jpg');">
                    <div class="service-content">
                        <h3>Medicina General</h3>
                        <hr class="linea">
                        <p>Atención preventiva y tratamiento de enfermedades comunes</p>
                    </div>    
                </div>
                <div class="service-card" style="background-image: url('assets/image/servicioPed.jpg');">
                    <div class="service-content">
                        <h3>Pediatría</h3>
                        <hr class="linea">
                        <p>Cuidado especializado para niños y adolescentes</p>
                    </div>
                </div>
                <div class="service-card" style="background-image: url('assets/image/servicioGin.jpg');">
                    <div class="service-content">
                        <h3>Ginecología</h3>
                        <hr class="linea">
                        <p>Salud reproductiva y cuidado femenino</p>
                    </div>
                </div>
                <div class="service-card" style="background-image: url('assets/image/servicioGer.jpg');">
                    <div class="service-content">
                        <h3>Geriatria</h3>
                        <hr class="linea">
                        <p>Cuidado especializado para adultos mayores</p>
                    </div>
                </div>
            </div>
        </section>

        <br>

        <section id="nosotros">
            <h2>Sobre Nosotros</h2>
            
            <div class="about-container">
                <div class="about-intro">
                    <p>Somos un equipo de profesionales de la salud comprometidos con tu bienestar. Con más de 20 años de experiencia, ofrecemos atención médica integral con tecnología de última generación.</p>
                </div>
                
                <div class="mission-vision">
                    <div class="mission-card">
                        <h3>Misión</h3>
                        <p>Brindar servicios médicos de alta calidad, centrados en la atención humana y personalizada, promoviendo la prevención y el cuidado integral de nuestros pacientes.</p>
                    </div>
                    
                    <div class="vision-card">
                        <h3>Visión</h3>
                        <p>Ser una institución médica de referencia a nivel nacional, reconocida por su innovación, excelencia profesional y compromiso con la salud y el bienestar de la comunidad.</p>
                    </div>
                </div>
            </div>
        </section>


    </div>

    <footer>
        <p>© 2025 Clínica Médica Salud Integral - Todos los derechos reservados</p>
        <p>Dirección: Calle Salud 123, Ciudad - Tel: 123-1234-5678</p>
    </footer>

    <!-- Modal de Login -->
    <div id="loginModal" class="modal">
        <div class="modal-content-1">
            <span class="close">&times;</span>
            <h2>Iniciar Sesión</h2>
            <form id="loginForm" method="post" action="backend/procesarLogin.php">
                <input type="email" placeholder="Correo electrónico"  name="correo" required>
                <input type="password" placeholder="Contraseña" name="password" required>
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </div>


    <!-- Modal de regisrto -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Crear Cuenta</h2>
            <form id="registroForm" method="post" action="backend/procesarRegistro.php">
                <div class="form-column">
                    <input type="text" placeholder="Nombre" name="nombre" required>
                    <input type="text" placeholder="Apellidos" name="apellidos" required>
                    <input type="email" placeholder="Correo electrónico" name="correo" required>
                    <input type="password" placeholder="Contraseña" name="password" required>
                    <input type="number" placeholder="Edad" name="edad" min="1" max="120" required>
                    
                    <select name="tipo_sangre">
                        <option value="">Tipo de sangre</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    
                    <input type="number" placeholder="Peso (kg)" name="peso" step="0.01" min="0">
                </div>
                
                <div class="form-column">
                    <textarea placeholder="Alergias conocidas" name="alergias" rows="3"></textarea>
                    <textarea placeholder="Enfermedades crónicas" name="enfermedades_cronicas" rows="3"></textarea>
                </div>
                
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>




    <script>

    // Carrusel del header (4 imágenes)
    let currentHeaderSlide = 0;
    const headerSlides = document.querySelectorAll('.header-slide');
    const totalSlides = headerSlides.length;

    // Solo la primera slide debe tener 'active' inicialmente
    headerSlides.forEach((slide, index) => {
        slide.classList.remove('active');
        if(index === 0) slide.classList.add('active');
    });

    function rotateHeaderSlides() {
        headerSlides[currentHeaderSlide].classList.remove('active');
        currentHeaderSlide = (currentHeaderSlide + 1) % totalSlides;
        headerSlides[currentHeaderSlide].classList.add('active');
    }

    setInterval(rotateHeaderSlides, 5000);

    // Control de Modales
    const modals = {
        login: document.getElementById("loginModal"),
        register: document.getElementById("registerModal")
    };

    // Función para cerrar todos los modales
    function closeAllModals() {
        Object.values(modals).forEach(modal => modal.style.display = "none");
    }

    // Controladores para los botones de login
    document.querySelectorAll('.login-btn, .login-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            closeAllModals();
            modals.login.style.display = "block";
        });
    });

    // Controladores para los botones de registro
    document.querySelectorAll('.register-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            closeAllModals();
            modals.register.style.display = "block";
        });
    });

    // Cerrar modales con la X
    document.querySelectorAll('.close').forEach(span => {
        span.onclick = () => {
            span.closest('.modal').style.display = "none";
        }
    });

    // Cerrar modales haciendo clic fuera
    window.onclick = (event) => {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }

    // Manejo del formulario de registro
document.getElementById('registroForm')?.addEventListener('submit', function(e) {
    e.preventDefault(); // evita que cambie de página

    const formData = new FormData(this);

    fetch('backend/procesarRegistro.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert('¡Registro exitoso!');
        closeAllModals(); // cierra el modal
    })
    .catch(err => {
        alert('Hubo un error');
        console.error(err);
    });
});

//manejo del boton del login 
document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;
        
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Validando...';
            
            const formData = new FormData(form);
            
            // Limpiar errores anteriores
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            const response = await fetch('backend/procesarLogin.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Error en la respuesta del servidor');
            
            if (data.status === 'success') {
                window.location.href = data.redirect;
            } else {
                throw new Error(data.message || 'Credenciales incorrectas');
            }
            
        } catch (error) {
            console.error('Error:', error);
            
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.textContent = error.message;
            
            form.parentNode.insertBefore(errorElement, form.nextSibling);
            
            // resaltar campos con error
            form.querySelectorAll('input').forEach(input => {
                if (input.value.trim() === '') {
                    input.classList.add('error');
                }
            });
            
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        }
    });


    // Manejo del formulario de registro
    /*document.getElementById('registroForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Validación básica
        if(!formData.get('nombre') || !formData.get('correo')) {
            alert('Por favor complete los campos requeridos');
            return;
        }
        
        // Simulación de envío
        console.log(Object.fromEntries(formData));
        alert('Registro exitoso (simulación)');
        closeAllModals();
    });*/
</script>


</body>
</html>