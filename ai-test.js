import OpenAI from "openai";

const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY,
});

async function run() {
  const response = await openai.responses.create({
    model: "gpt-5",
    input: "Hello bro, explain Laravel clean architecture simply."
  });

  console.log(response.output_text);
}

run();