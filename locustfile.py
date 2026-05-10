import re
import os
from locust import HttpUser, task, between
from dotenv import load_dotenv

# Carga las variables del archivo .env
load_dotenv()

class LaravelAuthUser(HttpUser):
    wait_time = between(1, 4)
    
    # Obtenemos los datos del .env
    test_email = os.getenv("LOCUST_TEST_EMAIL")
    test_password = os.getenv("LOCUST_TEST_PASSWORD")

    @task
    def login_stress_test(self):
        # 1. Obtener el formulario de login para extraer el token CSRF
        response = self.client.get("/login")
        
        # Extraer el token CSRF mediante regex
        csrf_token = re.search(r'name="_token" value="(.+?)"', response.text)
        
        if csrf_token:
            token_value = csrf_token.group(1)
            
            # 2. Realizar el POST usando las variables cargadas
            self.client.post("/login", {
                "_token": token_value,
                "email": self.test_email,
                "password": self.test_password
            })
        else:
            print("Error: No se encontró el token CSRF en la página")