from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time

# Initialize the WebDriver
driver = webdriver.Chrome()

# URL of the student registration page
url = "http://localhost/springwtj/FacultyConnect/App/View/auth/student_register.php"

try:
    # Open the registration page
    driver.get(url)
    driver.maximize_window()
    time.sleep(1)

    # Wait for the page to load
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "studentRegisterForm"))
    )
    time.sleep(0.5)

    # Locate the input fields
    student_id_field = driver.find_element(By.NAME, "student_id")
    name_field = driver.find_element(By.NAME, "name")
    dob_field = driver.find_element(By.NAME, "dob")
    gender_field = driver.find_element(By.NAME, "gender")
    nationality_field = driver.find_element(By.NAME, "nationality")
    email_field = driver.find_element(By.NAME, "email")
    phone_field = driver.find_element(By.NAME, "phone")
    address_field = driver.find_element(By.NAME, "address")
    emergency_contact_field = driver.find_element(By.NAME, "emergency_contact")
    department_field = driver.find_element(By.NAME, "department")
    education_field = driver.find_element(By.NAME, "education")
    guardian_name_field = driver.find_element(By.NAME, "guardian_name")
    guardian_phone_field = driver.find_element(By.NAME, "guardian_phone")
    profile_picture_field = driver.find_element(By.NAME, "student_picture")
    password_field = driver.find_element(By.NAME, "password")
    confirm_password_field = driver.find_element(By.NAME, "confirm_password")
    register_button = driver.find_element(By.XPATH, "//button[@type='submit']")

    # Test registration data
    test_data = {
        "student_id": "S-2024-009",
        "name": "Sumaya Tasnim",
        "dob": "2000-07-02",
        "gender": "Female",
        "nationality": "Bangladeshi",
        "email": "tasnim@gmail.edu",
        "phone": "016762127543",
        "address": "Dhaka City, Bangladesh",
        "emergency_contact": "016762127543",
        "department": "Computer Science",
        "education": "Undergraduate",
        "guardian_name": "Mahbubur Rahman",
        "guardian_phone": "016762127543",
        "profile_picture": r"C:\Users\Admin\Downloads\REG STUDENT.png",
        "password": "X9!qR@7L#P2m$A^v",
    }

    # Fill the form slowly
    student_id_field.send_keys(test_data["student_id"]); time.sleep(0.5)
    name_field.send_keys(test_data["name"]); time.sleep(0.5)
    dob_field.send_keys(test_data["dob"]); time.sleep(0.5)
    gender_field.send_keys(test_data["gender"]); time.sleep(0.5)
    nationality_field.send_keys(test_data["nationality"]); time.sleep(0.5)
    email_field.send_keys(test_data["email"]); time.sleep(0.5)
    phone_field.send_keys(test_data["phone"]); time.sleep(0.5)
    address_field.send_keys(test_data["address"]); time.sleep(0.5)
    emergency_contact_field.send_keys(test_data["emergency_contact"]); time.sleep(0.5)
    department_field.send_keys(test_data["department"]); time.sleep(0.5)
    education_field.send_keys(test_data["education"]); time.sleep(0.5)
    guardian_name_field.send_keys(test_data["guardian_name"]); time.sleep(0.5)
    guardian_phone_field.send_keys(test_data["guardian_phone"]); time.sleep(0.5)

    profile_picture_field.send_keys(test_data["profile_picture"])
    time.sleep(1)

    password_field.send_keys(test_data["password"]); time.sleep(0.5)
    confirm_password_field.send_keys(test_data["password"]); time.sleep(0.5)

    # Submit the form
    register_button.click()
    time.sleep(2)

    # Check result
    try:
        success_message = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CLASS_NAME, "success-message"))
        )
        print("✅ Student registration successful:", success_message.text)

    except TimeoutException:
        try:
            error_message = driver.find_element(By.CLASS_NAME, "error-message")
            print("❌ Student registration failed:", error_message.text)
        except NoSuchElementException:
            print("⚠️ No success or error message displayed.")

except TimeoutException:
    print("⚠️ Registration page took too long to load.")

finally:
    time.sleep(2)
    driver.quit()
