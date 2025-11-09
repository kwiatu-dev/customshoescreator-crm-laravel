const OPENAI_API_KEY = import.meta.env.VITE_OPENAI_API_KEY
import { ChatOpenAI } from '@langchain/openai'
import { ChatOllama } from '@langchain/ollama'
import { PromptTemplate } from '@langchain/core/prompts'
import { StructuredOutputParser } from '@langchain/core/output_parsers'
import { StringOutputParser } from '@langchain/core/output_parsers'
import { z } from 'zod'

const llm = new ChatOpenAI({
  model: 'gpt-3.5-turbo',
  apiKey: OPENAI_API_KEY,
  streaming: true,
})

// const llm = new ChatOllama({
//   model: 'gpt-oss:latest',
//   temperature: 0,
// })

const prompt = new PromptTemplate({
  inputVariables: ['topic'],
  template: 'Opisz temat w maksymalnie trzech zadaniach. Temat: {topic}',
})

const chain = prompt.pipe(llm).pipe(new StringOutputParser())
//const result = chain.invoke({ topic: 'uczenie maszynowe' })

//result.then(r => console.log(r))

// for await (const chunk of stream) {
//   console.log(chunk)
// }