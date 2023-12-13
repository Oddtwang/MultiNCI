def preprocess_examples(filepath, fields, splits = [0.6, 0.2], batch_size):
     whitespace = re.compile(' ')
     with open(filepath) as ofs:
        dataset = ofs.read()
        dataset = re.split(' EOS |\. ', dataset)
        examples_list = []
        for i in tqdm(range(len(dataset)), position=0, leave=True):
            sentence = dataset[i]
            sentence = '<sos> ' + sentence + ' <eos>'
            words = [x.start(0) for x in re.finditer(whitespace, sentence)]
            if len(words) > 150:
                continue
            for idx in range(1, len(words)-1):
                # fix why some targets are more than one element long
                examples_list.append(torchtext.data.Example.fromlist([sentence[:words[idx]], sentence[words[idx]+1: words[idx+1]]], fields))
     shuffle(examples_list)
     if len(examples_list)% batch_size != 0:
         examples_list = examples_list[:divmod(train_size, batch_size)[0]]
     train_size, dev_size = math.floor(splits[0]*len(examples_list)), math.floor(splits[1]*len(examples_list))
     train_set, dev_set, test_set = examples_list[:train_size], examples_list[train_size: train_size+dev_size], examples_list[train_size+dev_size:]

     return (train_set, dev_set, test_set)
