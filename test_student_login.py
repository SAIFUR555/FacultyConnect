from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time

def slow(seconds=2):
    time.sleep(seconds)

# Initialize the WebDriver
driver = webdriver.Chrome()

# URL of the login page
url = "http://localhost/springwtj/FacultyConnect/App/View/auth/Login.php"

try:
    print("Navigating to login page...")
    driver.get(url)
    driver.maximize_window()
    slow(3)

    print("Waiting for login form to load...")
    WebDriverWait(driver, 40).until(
        EC.presence_of_element_located((By.ID, "loginForm"))
    )
    slow(2)

    # Locate input fields and submit button
    user_id_field = driver.find_element(By.ID, "user_id")
    password_field = driver.find_element(By.ID, "password")
    login_button = driver.find_element(By.XPATH, "//button[@type='submit']")

    # Test credentials
    test_user_id = "S-2024-002"
    test_password = "X9!qR@7L#P2m$A^v"

    print("Filling in login credentials...")
    user_id_field.send_keys(test_user_id)
    slow(1.5)

    password_field.send_keys(test_password)
    slow(1.5)

    print("Submitting login form...")
    login_button.click()
    slow(4)

    print("Waiting for login response...")
    slow(3)

    # Validation
    try:
        error_message = driver.find_element(By.CLASS_NAME, "error-message")
        print("❌ Student login failed:", error_message.text)
    except NoSuchElementException:
        print("✅ Student login successful or no error message displayed.")

except TimeoutException:
    print("⚠️ The login page took too long to load.")

finally:
    print("Closing the browser in 5 seconds...")
    slow(5)
    driver.quit()
