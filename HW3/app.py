from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)

profile_data = {}

@app.route('/')
def index():
    return render_template('form.html')

@app.route('/create_profile', methods=['POST'])
def create_profile():
    global profile_data
    profile_data = {
        'firstname': request.form.get('firstname'),
        'lastname': request.form.get('lastname'),
        'sex': request.form.get('sex'),
        'status': request.form.get('status'),
        'location': request.form.get('location')
    }
    return redirect(url_for('profile'))

@app.route('/profile')
def profile():
    return render_template('profile.html', data=profile_data)

if __name__ == '__main__':
    app.run(debug=True)