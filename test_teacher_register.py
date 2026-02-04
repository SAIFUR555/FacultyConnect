from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
import os
import time

def slow(seconds=1):
    time.sleep(seconds)

# -----------------------------
# Configuration
# -----------------------------
URL = "http://localhost/springwtj/FacultyConnect/App/View/auth/teacher_register.php"
WAIT_TIME = 10
IMAGE_PATH = r"C:\Users\Admin\Downloads\574573715_1421302256183748_5349651728318277981_n.jpg"

TEST_DATA = {
    "teacher_id": "T-2024-2",
    "name": "Abdullah Al Jubayer",
    "dob": "1985-06-15",
    "gender": "Male",
    "nationality": "Bangladeshi",
    "email": "abdullah.jubayer@example.com",
    "phone": "01712345678",
    "department": "Computer Science",
    "qualifications": "PhD in Computer Science",
    "address": "AIUB, Dhaka, Bangladesh",
    "password": "X9!qR@7L#P2m$A^v",
    "confirm_password": "X9!qR@7L#P2m$A^v"
}

# -----------------------------
# Initialize WebDriver
# -----------------------------
driver = webdriver.Chrome()
driver.maximize_window()
wait = WebDriverWait(driver, WAIT_TIME)

try:
    print("Opening teacher registration page...")
    driver.get(URL)
    slow(2)

    wait.until(EC.presence_of_element_located((By.TAG_NAME, "form")))

    print("Filling registration form...")

    driver.find_element(By.NAME, "teacher_id").send_keys(TEST_DATA["teacher_id"])
    slow(0.8)

    driver.find_element(By.NAME, "name").send_keys(TEST_DATA["name"])
    slow(0.8)

    driver.find_element(By.NAME, "dob").send_keys(TEST_DATA["dob"])
    slow(0.8)

    Select(driver.find_element(By.NAME, "gender")) \
        .select_by_visible_text(TEST_DATA["gender"])
    slow(0.8)

    driver.find_element(By.NAME, "nationality").send_keys(TEST_DATA["nationality"])
    slow(0.8)

    driver.find_element(By.NAME, "email").send_keys(TEST_DATA["email"])
    slow(0.8)

    driver.find_element(By.NAME, "phone").send_keys(TEST_DATA["phone"])
    slow(0.8)

    driver.find_element(By.NAME, "department").send_keys(TEST_DATA["department"])
    slow(0.8)

    driver.find_element(By.NAME, "qualifications").send_keys(TEST_DATA["qualifications"])
    slow(0.8)

    driver.find_element(By.NAME, "address").send_keys(TEST_DATA["address"])
    slow(0.8)

    if os.path.exists(IMAGE_PATH):
        driver.find_element(By.NAME, "teacher_picture").send_keys(IMAGE_PATH)
        print("✓ Profile picture uploaded")
        slow(1.5)

    driver.find_element(By.ID, "password").send_keys(TEST_DATA["password"])
    slow(0.8)

    driver.find_element(By.ID, "confirm_password").send_keys(TEST_DATA["confirm_password"])
    slow(0.8)

    print("✓ All fields filled successfully")

    driver.find_element(By.CLASS_NAME, "submit-btn").click()
    slow(2)

    # -----------------------------
    # Validation
    # -----------------------------
    try:
        wait.until(EC.presence_of_element_located((By.CLASS_NAME, "success-message")))
        print("✅ Registration successful!")

    except TimeoutException:
        if "login" in driver.current_url.lower():
            print("✅ Redirected to login page — registration successful")
        else:
            print("⚠️ Registration submitted, but success message not detected")

    driver.save_screenshot("teacher_registration_result.png")

except Exception as e:
    print("❌ Error during test:", e)
    driver.save_screenshot("teacher_registration_error.png")

finally:
    slow(3)
    driver.quit()
    print("Browser closed. Test completed.")
