from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time

# Initialize the WebDriver (Make sure the appropriate WebDriver is installed and in PATH)
driver = webdriver.Chrome()

# URLs
login_url = "http://localhost/springwtj/FacultyConnect/App/View/auth/Login.php"
manage_slots_url = "http://localhost/springwtj/FacultyConnect/App/View/teacher/manage_slots.php"
logout_url = "http://localhost/springwtj/FacultyConnect/App/View/auth/Logout.php"

try:
    # Step 1: Log in as a teacher
    print("Navigating to login page...")
    driver.get(login_url)
    driver.maximize_window()
    print("Current URL after navigation:", driver.current_url)

    # Wait for the login page to load
    print("Waiting for login form to load...")
    WebDriverWait(driver, 40).until(EC.presence_of_element_located((By.ID, "loginForm")))

    # Locate the login fields and submit button
    user_id_field = driver.find_element(By.ID, "user_id")
    password_field = driver.find_element(By.ID, "password")
    login_button = driver.find_element(By.XPATH, "//button[@type='submit']")

    # Teacher login credentials
    teacher_user_id = "T-2024-1"
    teacher_password = "X9!qR@7L#P2m$A^v"

    # Fill in the login credentials
    print("Filling in login credentials...")
    user_id_field.send_keys(teacher_user_id)
    password_field.send_keys(teacher_password)

    # Submit the login form
    print("Submitting login form...")
    login_button.click()

    # Wait for the login to complete
    print("Waiting for login to complete...")
    try:
        WebDriverWait(driver, 40).until(EC.url_contains("dashboard"))  # Adjust the URL if needed
        print("Login successful. Redirected to dashboard.")
    except TimeoutException:
        print("Login failed or page did not redirect to dashboard.")
        raise

    # Step 2: Navigate to the manage slots page
    print("Navigating to manage slots page...")
    driver.get(manage_slots_url)
    print("Current URL after navigation:", driver.current_url)

    # Wait for the manage slots page to load
    print("Waiting for manage slots page to load...")
    WebDriverWait(driver, 40).until(EC.presence_of_element_located((By.TAG_NAME, "body")))

    # Step 3: Add a new slot
    print("Adding a new slot...")
    date_field = driver.find_element(By.ID, "date")
    time_field = driver.find_element(By.ID, "time")
    add_slot_button = driver.find_element(By.NAME, "add_slot")

    # Test slot details
    test_date = "2023-12-01"
    test_time = "10:00"

    # Fill in the slot details
    date_field.send_keys(test_date)
    time_field.send_keys(test_time)

    # Submit the form
    add_slot_button.click()

    # Wait for the success message
    print("Waiting for success message...")
    WebDriverWait(driver, 40).until(
        EC.presence_of_element_located((By.CLASS_NAME, "success-message"))
    )

    # Verify the success message
    try:
        success_message = driver.find_element(By.CLASS_NAME, "success-message")
        print("Slot added successfully:", success_message.text)
    except NoSuchElementException:
        print("Failed to add slot or no success message displayed.")
        raise

    # Step 4: Delete the newly added slot
    print("Deleting the newly added slot...")
    delete_buttons = driver.find_elements(By.NAME, "delete_slot")
    if delete_buttons:
        delete_buttons[-1].click()  # Click the last delete button (newly added slot)

        # Wait for the success message
        print("Waiting for delete success message...")
        WebDriverWait(driver, 40).until(
            EC.presence_of_element_located((By.CLASS_NAME, "success-message"))
        )

        # Verify the success message
        try:
            success_message = driver.find_element(By.CLASS_NAME, "success-message")
            print("Slot deleted successfully:", success_message.text)
        except NoSuchElementException:
            print("Failed to delete slot or no success message displayed.")
            raise
    else:
        print("No delete button found. Slot might not have been added.")

    # Step 5: Log out
    print("Logging out...")
    driver.get(logout_url)
    print("Logged out successfully. Current URL:", driver.current_url)

except TimeoutException:
    print("The page took too long to load. Check the URL or server status.")
finally:
    # Close the browser
    print("Closing the browser...")
    driver.quit()
