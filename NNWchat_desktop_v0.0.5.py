import tkinter as tk
from tkinter import messagebox
import requests
import json
import time

# Adresa PHP skriptu na webu pro odeslání zpráv
PHP_SCRIPT_URL = "http://nnwchat.nnwdev.fun/apps/web/send_message.php"
# URL pro přímé načítání JSON souboru
GET_MESSAGES_URL = "http://nnwchat.nnwdev.fun/apps/web/data.json"

# Funkce pro odesílání zprávy na server
def send_message():
    username = username_entry.get()
    message = message_entry.get()

    if not username or not message:
        messagebox.showerror("Chyba", "Vyplňte uživatelské jméno a zprávu.")
        return

    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    data = {
        "username": username,
        "message": message,
        "timestamp": timestamp
    }

    try:
        response = requests.post(PHP_SCRIPT_URL, data=data)
        response_data = response.json()

        if response.status_code == 200 and response_data.get("status") == "success":
            chat_window.config(state=tk.NORMAL)
            chat_window.insert(tk.END, f"{username} ({timestamp}): {message}\n")
            chat_window.config(state=tk.DISABLED)
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
                chat_window.insert(tk.END, f"{msg['username']} ({msg['timestamp']}): {msg['message']}\n")
            chat_window.config(state=tk.DISABLED)
            chat_window.yview(tk.END)  # Automatické posouvání na konec
    except Exception as e:
        messagebox.showerror("Chyba", f"Nastala chyba při načítání zpráv: {e}")

# Vytvoření hlavního okna
root = tk.Tk()
root.title("NNWchat")
root.iconbitmap("NNWchat.ico")
root.configure(bg="#333333")

# Uživatel a zpráva
username_label = tk.Label(root, text="Uživatelské jméno:", fg="white", bg="#333333")
username_label.grid(row=0, column=0, padx=5, pady=5)
username_entry = tk.Entry(root, bg="#555555", fg="white", insertbackground="white", width=30)
username_entry.grid(row=0, column=1, padx=5, pady=5)

message_label = tk.Label(root, text="Zpráva:", fg="white", bg="#333333")
message_label.grid(row=1, column=0, padx=5, pady=5)
message_entry = tk.Entry(root, bg="#555555", fg="white", insertbackground="white", width=30)
message_entry.grid(row=1, column=1, padx=5, pady=5)

send_button = tk.Button(root, text="Odeslat", command=send_message, bg="#4CAF50", fg="white", width=10)
send_button.grid(row=1, column=2, padx=5, pady=5)

# Vytvoření textového okna pro chat s rolováním
chat_window = tk.Text(root, bg="#222222", fg="white", state=tk.DISABLED, height=20, width=60, wrap=tk.WORD)
chat_scroll = tk.Scrollbar(root, command=chat_window.yview)
chat_window.config(yscrollcommand=chat_scroll.set)

chat_window.grid(row=2, column=0, columnspan=3, padx=10, pady=10)
chat_scroll.grid(row=2, column=3, sticky="nsew")

# Načtení zpráv při spuštění aplikace
load_messages()

# Spuštění hlavní smyčky
root.mainloop()


