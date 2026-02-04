from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
import time

# Initialize the WebDriver
driver = webdriver.Chrome()

# URL of the login page
url = "http://localhost/springwtj/FacultyConnect/App/View/auth/Login.php"

try:
    # Open the login page
    driver.get(url)
    driver.maximize_window()
    time.sleep(1)   # small delay

    # Wait for the page to load
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "loginForm"))
    )
    time.sleep(0.5)  # small delay

    # Locate the input fields and submit button
    user_id_field = driver.find_element(By.ID, "user_id")
    password_field = driver.find_element(By.ID, "password")
    login_button = driver.find_element(By.XPATH, "//button[@type='submit']")

    # Test credentials for a teacher
    test_user_id = "T-2024-1"
    test_password = "X9!qR@7L#P2m$A^v"

    # Fill in the credentials
    user_id_field.send_keys(test_user_id)
    time.sleep(0.5)

    password_field.send_keys(test_password)
    time.sleep(0.5)

    # Submit the form
    login_button.click()
    time.sleep(2)   # slightly increased wait

    # Check for error message or successful login
    try:
        error_message = driver.find_element(By.CLASS_NAME, "error-message")
        print("Teacher login failed with error:", error_message.text)
    except:
        print("Teacher login successful or no error message displayed.")

except TimeoutException:
    print("The login page took too long to load.")
finally:
    time.sleep(2)   # just enough to see result
    driver.quit()
