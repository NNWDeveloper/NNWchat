import tkinter as tk
from tkinter import messagebox
import requests
import json

# Adresa PHP skriptu na webu pro odeslání zpráv
PHP_SCRIPT_URL = "http://example.com/send_message.php"
# URL pro přímé načítání JSON souboru
GET_MESSAGES_URL = "http://example.com/data.json"

# Funkce pro odesílání zprávy na server
def send_message():
    username = username_entry.get()
    message = message_entry.get()

    if not username or not message:
        messagebox.showerror("Chyba", "Vyplňte uživatelské jméno a zprávu.")
        return

    data = {
        "username": username,
        "message": message
    }

    try:
        response = requests.post(PHP_SCRIPT_URL, data=data)
        response_data = response.json()

        if response.status_code == 200 and response_data.get("status") == "success":
            chat_window.insert(tk.END, f"{username}: {message}\n")
            message_entry.delete(0, tk.END)
            load_messages()  # Načíst zprávy po odeslání
        else:
            messagebox.showerror("Chyba", response_data.get("message", "Nepodařilo se odeslat zprávu."))
    except Exception as e:
        messagebox.showerror("Chyba", f"Nastala chyba při odesílání: {e}")

# Funkce pro načítání zpráv z JSON souboru
def load_messages():
    try:
        response = requests.get(GET_MESSAGES_URL)
        if response.status_code == 200:
            messages = response.json()
            chat_window.config(state=tk.NORMAL)
            chat_window.delete(1.0, tk.END)  # Vymazání starých zpráv
            for msg in messages:
                chat_window.insert(tk.END, f"{msg['username']}: {msg['message']}\n")
            chat_window.config(state=tk.DISABLED)
    except Exception as e:
        messagebox.showerror("Chyba", f"Nastala chyba při načítání zpráv: {e}")

# Vytvoření hlavního okna
root = tk.Tk()
root.title("Chatovací aplikace")
root.configure(bg="black")

# Uživatel a zpráva
username_label = tk.Label(root, text="Uživatelské jméno:", fg="white", bg="black")
username_label.grid(row=0, column=0, padx=5, pady=5)
username_entry = tk.Entry(root, bg="black", fg="white", insertbackground="white")
username_entry.grid(row=0, column=1, padx=5, pady=5)

message_label = tk.Label(root, text="Zpráva:", fg="white", bg="black")
message_label.grid(row=1, column=0, padx=5, pady=5)
message_entry = tk.Entry(root, bg="black", fg="white", insertbackground="white")
message_entry.grid(row=1, column=1, padx=5, pady=5)

send_button = tk.Button(root, text="Odeslat", command=send_message, bg="white", fg="black")
send_button.grid(row=1, column=2, padx=5, pady=5)

# Chat okno
chat_window = tk.Text(root, bg="black", fg="white", state=tk.NORMAL, height=20, width=50)
chat_window.grid(row=2, column=0, columnspan=3, padx=10, pady=10)

# Načtení zpráv při spuštění aplikace
load_messages()

# Spuštění hlavní smyčky
root.mainloop()
