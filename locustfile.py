import os
import re

from locust import HttpUser, between, task


def env(name, default=None):
    value = os.getenv(name)
    return value if value not in (None, "") else default


def csrf_token_from(html):
    match = re.search(r'name="_token"\s+value="([^"]+)"', html)
    return match.group(1) if match else None


class LaravelWebUser(HttpUser):
    """Simulates a user browsing the Laravel web application."""

    host = env("LOCUST_HOST", "http://localhost:8081")
    wait_time = between(1, 4)

    login_path = env("LOCUST_LOGIN_PATH", "/login")
    email = env("LOCUST_TEST_EMAIL")
    password = env("LOCUST_TEST_PASSWORD")

    def on_start(self):
        if self.email and self.password:
            self.login()

    def login(self):
        with self.client.get(
            self.login_path,
            name="GET /login",
            catch_response=True,
        ) as response:
            token = csrf_token_from(response.text)

            if not token:
                response.failure("CSRF token was not found on the login page")
                return

        with self.client.post(
            self.login_path,
            data={
                "_token": token,
                "email": self.email,
                "password": self.password,
            },
            name="POST /login",
            catch_response=True,
        ) as login_response:
            if login_response.status_code >= 400:
                login_response.failure(
                    f"Login failed with status {login_response.status_code}"
                )

    def ensure_credentials(self):
        if self.email and self.password:
            return True

        self.client.get("/", name="GET /")
        return False

    @task(3)
    def dashboard(self):
        if not self.ensure_credentials():
            return

        with self.client.get(
            "/dashboard",
            name="GET /dashboard",
            catch_response=True,
        ) as response:
            if response.url.endswith("/login"):
                response.failure("User was redirected to login")


    @task(2)
    def dashboard_api(self):
        if not self.ensure_credentials():
            return

        self.client.get("/api/dashboard", name="GET /api/dashboard")

    @task(1)
    def filters(self):
        if not self.ensure_credentials():
            return

        self.client.get("/api/filters/years", name="GET /api/filters/years")


class LaravelApiUser(HttpUser):
    """Simulates API clients using the Sanctum token endpoint."""

    host = env("LOCUST_HOST", "http://localhost:8081")
    wait_time = between(1, 3)

    login_path = env("LOCUST_API_LOGIN_PATH", "/api/login")
    email = env("LOCUST_TEST_EMAIL")
    password = env("LOCUST_TEST_PASSWORD")

    def on_start(self):
        self.headers = {}

        if self.email and self.password:
            self.login()

    def login(self):
        with self.client.post(
            self.login_path,
            json={
                "email": self.email,
                "password": self.password,
            },
            name="POST /api/login",
            catch_response=True,
        ) as response:
            if response.status_code != 200:
                response.failure(f"API login failed with status {response.status_code}")
                return

            try:
                token = response.json().get("access_token")
            except ValueError:
                response.failure("API login response was not valid JSON")
                return

            if not token:
                response.failure("API login did not return an access token")
                return

            self.headers = {"Authorization": f"Bearer {token}"}

    @task(3)
    def monthly_expenses(self):
        if not self.headers:
            return

        self.client.get(
            "/api/monthly-expenses",
            headers=self.headers,
            name="GET /api/monthly-expenses",
        )

    @task(1)
    def current_user(self):
        if not self.headers:
            return

        self.client.get("/api/user", headers=self.headers, name="GET /api/user")
