while True:
    print("Enter dollar ($) (* to exit):")
    str = input()

    if str == '*':
        print("Bye")
        break

    strlist = str.split('@')

    mytuple = tuple(int(elements) for elements in strlist)

    def convert(x):
        rupees = x*88.15
        pound = x*0.74
        yuan = x*7.12
        return (rupees, pound, yuan)

    print(f"{'Dollar ($)':<12} {'Indian Rupee (R)':<20} {'British (Pound)':<20} {'China (Y)':<20}")

    for i in mytuple:
        converted = convert(i)
        print(f"{i:<12} {converted[0]:<20} {converted[1]:<20} {converted[2]:<20}")

