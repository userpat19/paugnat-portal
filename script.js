



let age = 16;
try{
    if (age < 18) throw new Error("You must be at least 18 years old.");
    console.log("Access granted.");
} catch (error) {
    console.error(error.message);
}