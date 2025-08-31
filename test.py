
task = []
while True:
    x = input("Welcome! Select task[1,2,3]: ")
    if x == '1':
        y = input("You pressed 1! Add task: ")
        task.append(y)

    elif x == '2':
        print("You pressed 2!")
        for i in range(len(task)):
            print(task[i])

    elif x == '3':
        print("Exit")
        break